<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementPaymentRequest;
use App\Models\Procurement\Payment;
use App\Models\Procurement\Procurement;
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
        $data = [
            'count_id' => $current_id + 1,
            'new_code' => "PY-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
            'procurement_id'  => $request->procurement_id,
            'procurement_code'  =>  Procurement::where('id', $request->procurement_id)->value('code'),
        ];
        return view('procurement.payment.index')->with(["procurement" => Procurement::find(1)->get(), "data" => $data]);
    }


    public function store(StoreProcurementPaymentRequest $request){

       // return $request->all();
        if ($payment = Payment::create($request->all())){

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
