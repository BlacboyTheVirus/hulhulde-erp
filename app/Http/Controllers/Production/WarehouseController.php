<?php

namespace App\Http\Controllers\Production;

use App\Enums\ProcurementNext;
use App\Enums\ProductionNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionWarehouseRequest;
use App\Models\Production\Production;
use App\Models\Production\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            //Datatables
            return $this->getProductions();
        }

        return view('production.warehouse.index' );
    }

    public function create(Request $request)
    {
        //Current ID
        $current_id = Warehouse::max('count_id');
        if(!$current_id) $current_id = 0;

        //Production details
        $prod = Production::with( 'warehouse')->find($request->id);
        $data = [
            'count_id' => $current_id + 1,
            'new_code' => "PWH-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
            'production_id'  => $request->id,
            'production_code'=> $prod->code,
            'requested_weight' => $prod->requested_weight,

        ];

        return view('production.warehouse.create', compact('data' ));
    }


    public function store(StoreProductionWarehouseRequest $request){


        if ($warehouse = Warehouse::create($request->all())){
            $warehouse->production->next = ProductionNext::OUTPUT;
            $warehouse->production->save();

            //Update Stock Level in Input
            $initial_quantity = $warehouse->production->input->quantity;
            $warehouse->production->input->quantity = $initial_quantity - $request->weight;
            $warehouse->production->input->save();

            return response(["success"=> true, "message" => "Warehouse record created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Warehouse record."], 200);
    }


    private function getProductions(): JsonResponse
    {
        $data = Production::with( 'input','warehouse');

        return DataTables::eloquent($data)

            ->addColumn('production_date', function($row){
                return Carbon::parse($row->production_date)->format('d-M-Y');
            })

            ->addColumn('input', function ($row) {
                return $row->input->name;
            })

            ->addColumn('released_weight', function ($row) {
                return ($row->warehouse->weight ?? '-');
            })

            ->addColumn('action', function($row){
                $action = "";

                if ($row->next == ProductionNext::WAREHOUSE && !isset($row->warehouse) ) { // If Warehouse is next & warehouse info not  added
                    $action .= "<a class='btn btn-xs btn-success' href='" . route('production.warehouse.create', ['id' => $row->id]) . "'><i class='fas fa-warehouse'></i></a> ";
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
            ->rawColumns([ 'action', 'status'])
            ->make('true');
    }



}
