<?php

namespace App\Http\Controllers\Procurement;

use App\Enums\ProcurementNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementWarehouseRequest;
use App\Models\Procurement\Procurement;
use App\Models\Procurement\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
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

        return view('procurement.warehouse.index' );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //Current ID
        $current_id = Warehouse::max('count_id');
        if(!$current_id) $current_id = 0;

        //Procurement details
        $proc = Procurement::with('supplier', 'warehouse', 'weighbridge', 'quality')->find($request->id);
        $data = [
            'count_id' => $current_id + 1,
            'new_code' => "WH-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
            'procurement_id'  => $request->id,
            'procurement_code'=> $proc->code,
            'supplier'        => $proc->supplier->name,
            'confirmed_weight' => $proc->weighbridge->weight - $proc->quality->rejected_weight,
            'confirmed_bags'   => $proc->weighbridge->bags - $proc->quality->rejected_bags
        ];

        return view('procurement.warehouse.create', compact('data' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcurementWarehouseRequest $request)
    {

        if ($warehouse = Warehouse::create($request->all())){
            $warehouse->procurement->next = ProcurementNext::ACCOUNT;
            $warehouse->procurement->save();

            //Update Stock Level in Input
            $initial_quantity = $warehouse->procurement->input->quantity;
            $warehouse->procurement->input->quantity = $initial_quantity + $request->weight;
            $warehouse->procurement->input->save();

            //update Amount due on procurement
            $warehouse->procurement->amount = $warehouse->weight * $warehouse->procurement->quality->recommended_price;
            $warehouse->procurement->save();

            return response(["success"=> true, "message" => "Warehouse record created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Warehouse record"], 200);
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
        $data = Procurement::with('supplier', 'input', 'weighbridge', 'quality', 'warehouse');

        return DataTables::eloquent($data)
            ->addColumn('supplier', function (Procurement $procurement) {
                return $procurement->supplier->name;
            })

            ->addColumn('procurement_date', function($row){
                return Carbon::parse($row->procurement_date)->format('d-M-Y');
            })

            ->addColumn('expected_bags', function(Procurement $procurement){
                return( ($procurement->weighbridge && $procurement->quality)  ? $procurement->weighbridge->bags - $procurement->quality->rejected_bags : $procurement->expected_bags);
            })

            ->addColumn('expected_weight', function(Procurement $procurement){
                return( ($procurement->weighbridge && $procurement->quality) ? $procurement->weighbridge->weight - $procurement->quality->rejected_weight : $procurement->expected_weight);
            })

            ->addColumn('input', function (Procurement $procurement) {
                return $procurement->input->name;
            })
            ->addColumn('status', function($row){
                return ($row->status == "open" ? "<small class='badge badge-warning'>Open</small>" : "<small class='badge badge-danger'>Closed</small>");
            })

            ->addColumn('action', function($row){
                $action = "";

                if ($row->next == ProcurementNext::WAREHOUSE && !isset($row->warehouse) ) { // If Warehouse is next & warehouse info not  added
                    $action .= "<a class='btn btn-xs btn-success' href='" . route('procurement.warehouse.create', ['id' => $row->id]) . "'><i class='fas fa-warehouse'></i></a> ";
                }

                if (isset($row->warehouse)){ // If quality info has been added
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
            ->rawColumns([ 'action', 'status', 'expected_weight', 'expected_bags'])
            ->make('true');
    }
}
