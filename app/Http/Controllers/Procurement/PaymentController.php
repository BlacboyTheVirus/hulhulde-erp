<?php

namespace App\Http\Controllers\Procurement;

use App\Enums\ProcurementPaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementPaymentRequest;
use App\Models\Procurement\Payment;
use App\Models\Procurement\Procurement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->getPayments($request->procurement_id);
        }

        //Current ID
        $current_id = Payment::max('count_id');
        if(!$current_id) $current_id = 0;


       $procurement  = Procurement::find($request->procurement_id);
       $supplier = $procurement->supplier;

        $data = [
            'count_id' => $current_id + 1,
            'new_code' => "PY-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
            'procurement_id'  => $procurement->id,
            'procurement_code'  => $procurement->code,
        ];
        return view('procurement.payment.index')->with(["procurement" => $procurement, "data" => $data, "supplier"=> $supplier]);
    }


    public function store(StoreProcurementPaymentRequest $request){

       // return $request->all();


        if ($payment = Payment::create($request->all())){

            //if Payment Type is ADVANCE, update advance
            if($payment->payment_type == ProcurementPaymentType::ADVANCE){
                $supplier = Supplier::find($request->supplier_id);
                $available_advance = $supplier->value('advance');

                //check if payment type is advance and its not more than available
                if ($request->amount > $available_advance){
                    return response(["success"=> false, "message" => "Insufficient advance balance!"], 200);
                }

                $updated_advance = $available_advance - $request->amount;
                $supplier->update(['advance' => $updated_advance]);
            }


            return response(["success"=> true, "message" => "Payment created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Payment!"], 200);
    }


    private function getPayments($procurement_id)
    {
        $data = Payment::where('procurement_id', $procurement_id)->get();
//        return $data;
        return DataTables::of($data)



            ->addColumn('action', function($row){
                $action = "";

                    if(Auth::user()->can('users.edit')){
                        $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
                    }

                    if(Auth::user()->can('users.destroy')){
                        $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                    }

                return $action;
            })
            ->rawColumns(['action'])->make('true');
    }

}
