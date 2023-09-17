<?php

namespace App\Http\Controllers\Procurement;

use App\Enums\ProcurementNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementWeighbridgeRequest;
use App\Models\Procurement\Procurement;
use App\Models\Procurement\Weighbridge;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WeighbridgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            //Datatables
            return $this->getProcurements();
        }

        return view('procurement.weighbridge.index' );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //Current ID
        $current_id = Weighbridge::max('count_id');
        if(!$current_id) $current_id = 0;

        //Procurement details
        $proc = Procurement::with('supplier')->find($request->id);
        $data = [
            'count_id' => $current_id + 1,
            'new_code' => "WB-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
            'procurement_id'  => $request->id,
            'procurement_code'=> $proc->code,
            'supplier'        => $proc->supplier->name,
            'expected_weight' => $proc->expected_weight,
            'expected_bags'   => $proc->expected_bags
        ];

        return view('procurement.weighbridge.create', compact('data' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcurementWeighbridgeRequest $request)
    {
        //return $request;
        if ($weighbridge = Weighbridge::create($request->all())){
            $weighbridge->procurement->next = ProcurementNext::QUALITY;
            $weighbridge->procurement->save();

            return response(["success"=> true, "message" => "Weighbridge record created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Weighbridge record"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    private function getProcurements(): JsonResponse
    {
        $data = Procurement::with('supplier', 'input', 'weighbridge');

        return DataTables::eloquent($data)
            ->addColumn('supplier', function (Procurement $procurement) {
                return $procurement->supplier->name;
            })

            ->addColumn('procurement_date', function($row){
                return Carbon::parse($row->procurement_date)->format('d-M-Y');
            })

            ->addColumn('input', function (Procurement $procurement) {
                return $procurement->input->name;
            })
            ->addColumn('status', function($row){
                return ($row->status == "open" ? "<small class='badge badge-warning'>Open</small>" : "<small class='badge badge-danger'>Closed</small>");
            })

            ->addColumn('action', function($row){
                $action = "";

                if ($row->next == ProcurementNext::WEIGHBRIDGE && !isset($row->weighbridge) ) { // If Security is next & security info not  added
                    $action .= "<a class='btn btn-xs btn-success' href='" . route('procurement.weighbridge.create', ['id' => $row->id]) . "'><i class='fas fa-weight'></i></a> ";
                }

                if (isset($row->weighbridge) ){ // If security info has been added
                    if(Auth::user()->can('users.show')){
                        $action .= "<a class='btn btn-xs btn-outline-info' id='btnShow' href='" . route('users.show', $row->id) . "'><i class='fas fa-eye'></i></a> ";
                    }
                    if(Auth::user()->can('users.edit')){
                        $action.="<a class='btn btn-xs btn-outline-warning' id='btnEdit' href='".route('users.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
                    }
                    if(Auth::user()->can('users.destroy')){
                        $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                    }
                }

                return $action;
            })
            ->rawColumns([ 'action', 'status'])
            ->make('true');
    }
}
