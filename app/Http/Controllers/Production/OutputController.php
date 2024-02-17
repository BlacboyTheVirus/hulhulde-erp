<?php

namespace App\Http\Controllers\Production;

use App\Enums\ProductionNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionOutputRequest;
use App\Models\Product;
use App\Models\Production\Output;
use App\Models\Production\Production;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OutputController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            //Datatables
            return $this->getProductions();
        }

        return view('production.output.index' );
    }


    public function create(Request $request)
    {
        //Outputs
        $outputs = Product::all();

        //Production details
        $production = Production::find($request->id);
        $data = [
            'production_id'  => $production->id,
            'production_code'=> $production->code,
            'production_date'=> $production->production_date,
            'weight' => $production->warehouse->weight,

        ];

        return view('production.output.create', compact('data','outputs' ));
    }



    public function store(StoreProductionOutputRequest $request){

       // return $request->all();

        //OUTPUT DETAILS
        $output_items = [];
        foreach ($request->output_id as $key => $value) {
            $output_items[$key]['production_id'] = $request->production_id;
            $output_items[$key]['production_date'] = $request->production_date;
            $output_items[$key]['shift'] = $request->shift;
            $output_items[$key]['product_id'] = $request->output_id[$key];
            $output_items[$key]['bags'] = $request->bags[$key];
            $output_items[$key]['weight'] = $request->weight[$key];
            $output_items[$key]['user_id'] = $request->user_id;
        }

        //return ($output_items);

        $production = Production::find($request->production_id);

        if ($outputs = $production->outputs()->createMany($output_items)){ //changed from createMany
            $production->next = ProductionNext::STORE;
            $production->save();


            //update product stock
//            foreach ($outputs as $output ){
//                $product = Product::find($output->product_id);
//                $product->weight = $product->weight + $output->weight;
//                $product->bags = $product->bags + $output->bags;
//                $product->save();
//            }
//


            return response(["success"=> true, "message" => "Output records created successfully."], 200);
        }

        return response(["success"=> false, "message" => "Error creating Output records."], 200);

    }






    private function getProductions(): JsonResponse
    {
        $data = Production::with( 'input','warehouse', 'outputs');

        return DataTables::eloquent($data)

            ->addColumn('production_date', function($row){
                return Carbon::parse($row->production_date)->format('d-M-Y');
            })

            ->addColumn('input', function ($row) {
                return $row->input->name;
            })

            ->addColumn('released_weight', function ($row) {
                return ($row->warehouse->weight ?? 0);
            })

            ->addColumn('output_weight', function ($row) {
                return ($row->outputs->sum('weight') ?? 0) ;
            })



            ->addColumn('action', function($row){
                $action = "";

//                if ($row->next == ProductionNext::OUTPUT && (count($row->outputs) == 0) ) { // If Warehouse is next & warehouse info not  added
                    $action .= "<a class='btn btn-xs btn-success' href='" . route('production.output.create', ['id' => $row->id]) . "'><i class='fas fa-box-open'></i></a> ";
//                }

                if (count($row->outputs) > 0){ // If quality info has been added
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
