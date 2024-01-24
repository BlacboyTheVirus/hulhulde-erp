<?php

namespace App\Http\Controllers\Sales;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceStoreRequest;
use App\Models\Product;
use App\Models\Sales\Invoice;
use App\Models\Sales\InvoiceItem;
use App\Models\Sales\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            //Datatables for All Invoices
            return  $this->getInvoices();
        }


        return view('marketing.store.index' );
    }



    public function create(Request $request)
    {
        //Outputs
        $products = Product::all();

        //Invoice details
        $invoice = Invoice::with('invoiceitems')->findOrFail($request->id);
        $data = [
            'invoice_id'  => $invoice->id,
            'invoice_code'=> $invoice->code,
            'invoice_date'=> $invoice->production_date,
        ];

        //invoice release details
        $releases = Invoice::with('stores')->find($invoice->id)->stores;


        //return $invoice;

//        $invoiceitems = DB::table('production_outputs')
//            ->join('products', 'production_outputs.product_id', '=', 'products.id')
//            ->whereRaw('production_outputs.production_id = '.$request->id )
//            ->select('production_outputs.*', 'products.name')
//            ->get();


        return view('marketing.store.create', compact('data','invoice', 'products', 'releases' ));
    }


    public function store(StoreInvoiceStoreRequest $request)
    {
        //OUTPUT DETAILS
        $product_items = [];

       // return $request;

        foreach ($request->product_id as $key => $value) {
            $product_items[$key]['invoice_id'] = $request->invoice_id;
            $product_items[$key]['released_date'] = $request->released_date;
            $product_items[$key]['product_id'] = $request->product_id[$key];
            $product_items[$key]['weight'] = $request->bag_weight[$key] * $request->quantity[$key];
            $product_items[$key]['quantity'] = $request->quantity[$key];
            $product_items[$key]['released_by'] = $request->released_by;
            $product_items[$key]['user_id'] = auth()->id();

        }

      //return $product_items;

        $invoice = Invoice::find($request->invoice_id);



        if ($stores = $invoice->stores()->createMany($product_items)) { // changed from createMany

            //update product stock
            foreach ($stores as $store) {
                $product = Product::find($store->product_id);
                $product->weight = $product->weight - $store->weight;
                $product->bags = $product->bags - $store->quantity;
                $product->save();

            }


            //update invoice_items quantity_left to collect
            $all = [];
            foreach ($stores as $store) {
                $invoice_item = InvoiceItem::where(['invoice_id' => $store->invoice_id, 'product_id' => $store->product_id ])->get();
                foreach($invoice_item as $item){
                    $item->quantity_left = $item->quantity_left - $store->quantity;
                    $item->save();
                }
            }
            return response(["success" => true, "message" => "Store records created successfully."], 200);
        }

    }

    private function getInvoices(): JsonResponse
    {
        $data = Invoice::with( 'invoiceitems', 'customer', 'stores');

        return DataTables::eloquent($data)

            ->addColumn('date', function($row){
                return Carbon::parse($row->date)->format('d-M-Y');
            })

            ->addColumn('name', function ($row) {
                return $row->customer->name;
            })

            ->addColumn('action', function($row){
                $action = "";

                if ( (count($row->stores) >= 0) &&  ($row->payment_status != PaymentStatus::UNPAID)  ) {
                    $action .= "<a class='btn btn-xs btn-success' href='" . route('marketing.store.create', ['id' => $row->id]) . "'><i class='fas fa-box-open'></i></a> ";
                }

//                if ( (count($row->stores) > 0)  ){
//                    $action .= " DISPATCHED ";
//                }


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }






}
