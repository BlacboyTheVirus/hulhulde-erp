<?php /** @noinspection ALL */

namespace App\Http\Controllers\Production;

use App\Enums\ProcurementNext;
use App\Enums\ProductionNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementRequest;
use App\Http\Requests\StoreProductionRequest;
use App\Models\Input;
use App\Models\Procurement\Approval;
use App\Models\Procurement\Procurement;
use App\Models\Production\Production;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()){
            //Datatables
            return  $this->getProductions();
        }

        return view('production.index' );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Current ID
        $current_id = Production::max('count_id');
        if(!$current_id) $current_id = 0;
        $data = [
            'count_id'      => $current_id + 1,
            'new_code' =>  "PM-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
        ];

        //Item List
        $inputs = Input::all();
        $available_paddy = $inputs->where('name','=','Paddy')->value('quantity');

        return view('production.create', compact('data', 'inputs', 'available_paddy' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionRequest $request)
    {
        if ($production = Production::create($request->all())){

            return response(["success"=> true, "message" => "Production created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Production!"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Procurement $procurement)
    {
        return view('procurement.show')->with(['procurement'=>$procurement]);
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

    private function getProductions(): JsonResponse
    {
        $data = Production::with( 'input', 'warehouse', 'outputs');

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

            ->addColumn('output_weight', function ($row) {
                return ($row->outputs->sum('weight') ?? 0) ;
            })


            ->addColumn('action', function($row){
                $action = "";

                $action .= "<div class='btn-group dropdown'><button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button> <div class='dropdown-menu'><h6 class='dropdown-header'>Actions for ".$row->code."</h6><div class='dropdown-divider'></div>";

                $action .= "<a class='dropdown-item' data-id= '" . $row->id . "' href='" . route('procurement.show', $row->id). "'  ><i class= 'fas fa-eye mr-2'></i>View Production</a>";

                 $action .="<a class='dropdown-item' data-id= '" . $row->id . "' href='edit/" .  $row->id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>";


                $action .=" <a class='dropdown-item' onclick='delete_invoice(".$row->id.")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>";

                $action .="</div> </div>";


                return $action;
            })
            ->rawColumns([ 'action', 'status'])
            ->make('true');
    }
}
