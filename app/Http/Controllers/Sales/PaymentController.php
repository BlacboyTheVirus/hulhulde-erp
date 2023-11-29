<?php

namespace App\Http\Controllers\Sales;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoicePaymentRequest;
use App\Models\Sales\Invoice;
use App\Models\Sales\Payment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax() && $request->id){
            //Datatables for Specific Invoice Payments
            return  $this->getPayments($request->id);
        } elseif ($request->ajax()){
            //Datatables for All Invoices
            return  $this->getInvoices();
        }

        return view('marketing.payment.index' );

    }


    public function create(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);

        //Current ID
        $current_id = Payment::max('count_id');
        if(!$current_id) $current_id = 0;
        $data = [
            'count_id'      => $current_id + 1,
            'new_code'      =>  "PS-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
        ];

        //return (compact ('data', 'products'));
        return view('marketing.payment.create', compact('data','invoice' ));
    }



    public function store(StoreInvoicePaymentRequest $request){

        //return $request->all();

        if ($payment = Payment::create($request->all())){

            $total_paid =  $payment->invoice->amount_paid + $request->amount;
            $payment->invoice->amount_paid = $total_paid;
            $payment->invoice->amount_due = $payment->invoice->amount_due - $request->amount;
            if ($total_paid >= $payment->invoice->grand_total){
                $payment->invoice->payment_status = PaymentStatus::PAID;
            } else {
                $payment->invoice->payment_status = PaymentStatus::PARTIAL;
            }
            $payment->invoice->save();

            return response(["success"=> true, "message" => "Payment created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Payment!"], 200);
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


                    $action .= "<a class='btn btn-xs btn-success' href='" . route('marketing.payment.create', ['id' => $row->id]) . "'><i class='fas fa-money-bill-wave'></i></a> ";

                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }



    private function getPayments($id): JsonResponse
    {
        $data = Payment::where('invoice_id', '=', $id);

        return DataTables::eloquent($data)

            ->addColumn('payment_date', function($row){
                return Carbon::parse($row->payment_date)->format('d-M-Y');
            })


            ->addColumn('action', function($row){
                $action = "";

                    $action .= "<a class='btn btn-xs btn-success' href='" . route('marketing.payment.edit', ['payment' => $row->id]) . "'><i class='fa fa-edit'></i></a> ";

                $action .= "<a class='btn btn-xs btn-danger' href='" . route('marketing.payment.destroy', ['payment' => $row->id]) . "'><i class='fa fa-trash'></i></a> ";


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }
}
