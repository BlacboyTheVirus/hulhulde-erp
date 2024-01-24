<?php

namespace App\Http\Controllers\Sales;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Input;
use App\Models\Product;
use App\Models\Production\Production;
use App\Models\Sales\Invoice;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()){
            //Datatables
            return  $this->getInvoices();
        }

        return view('marketing.invoice.index' );

    }

    public function show (Invoice $invoice){
        //$invoiceitems = $invoice->with('invoiceitems');
        return view('marketing.invoice.show')->with(['invoice' => $invoice] );
    }



    public function create(Request $request)
    {

        //Current ID
        $current_id = Invoice::max('count_id');
        if(!$current_id) $current_id = 0;
        $data = [
            'count_id'      => $current_id + 1,
            'new_code' =>  "IN-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
        ];

        //Products List
        $products = Product::all();

        //return (compact ('data', 'products'));
        return view('marketing.invoice.create', compact('data','products' ));
    }



    public function store(StoreInvoiceRequest $request){

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'count_id' => $request->count_id,
                'code' => $request->code,
                'date' => $request->date,
                'sub_total' => $request->subtotal,
                'discount' => $request->discount,
                'grand_total' => $request->grandtotal,
                'amount_paid' => 0.00,
                'amount_due' => $request->grandtotal,
                'payment_status' => PaymentStatus::UNPAID,
                'note' => $request->note,
                'user_id' => $request->user_id,
            ]);


            //INVOICE ITEMS DETAILS
            $product_id = $request->product_id;
            $unit_price = $request->unit_price;
            $quantity = $request->quantity;
            $unit_amount = $request->unit_amount;
            $product_bagweight = $request->product_bagweight;


            $invoiceitems = [];
            foreach ($product_id as $key => $value) {
                $invoiceitems[$key]['product_id'] = $product_id[$key];
                $invoiceitems[$key]['unit_price'] = $unit_price[$key];
                $invoiceitems[$key]['quantity'] = $quantity[$key];
                $invoiceitems[$key]['quantity_left'] = $quantity[$key];
                $invoiceitems[$key]['unit_amount'] = $unit_amount[$key];
                $invoiceitems[$key]['weight'] = round($product_bagweight[$key] * $quantity[$key], 2);
            }


            $invoice->invoiceitems()->createMany($invoiceitems);

            DB::commit();

            return response(["success"=> true, "lastid" => $invoice->id , "message" => "Invoice created successfully."], 200);

        }catch(Exception $ex){
            DB::rollBack();
            //throw $ex;
            return response(["success"=> false, "message" => "Error creating Invoice."], 200);

        } //END DB TRANSACTIONS



    }


    private function getInvoices(): JsonResponse
    {
        $data = Invoice::with( 'invoiceitems', 'customer');

        return DataTables::eloquent($data)

            ->addColumn('date', function($row){
                return Carbon::parse($row->date)->format('d-M-Y');
            })

            ->addColumn('name', function ($row) {
                return $row->customer->name;
            })

            ->addColumn('action', function($row){
                $action = "";

                $action .= "<div class='btn-group dropdown'><button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button> <div class='dropdown-menu'><h6 class='dropdown-header'>Actions for ".$row->code."</h6><div class='dropdown-divider'></div>";

                $action .= "<a class='dropdown-item' data-id= '" . $row->id . "' href='" . route('marketing.invoice.show', $row->id). "'  ><i class= 'fas fa-eye mr-2'></i>View Invoice</a>";

                $action .= "<a class='dropdown-item' data-id= '" . $row->id . "' href='" . route('marketing.invoice.show', $row->id). "'  ><i class= 'fa fa-money-bill-wave-alt mr-2'></i>Payments</a>";

                $action .="<a class='dropdown-item' data-id= '" . $row->id . "' href='edit/" .  $row->id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>";


                $action .=" <a class='dropdown-item' onclick='delete_invoice(".$row->id.")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>";

                $action .="</div> </div>";


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }

}
