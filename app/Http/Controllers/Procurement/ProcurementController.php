<?php /** @noinspection ALL */

namespace App\Http\Controllers\Procurement;

use App\Enums\ProcurementNext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProcurementRequest;
use App\Models\Input;
use App\Models\Procurement\Approval;
use App\Models\Procurement\Procurement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()){
            //Datatables
            return  $this->getProcurements();
        }

        return view('procurement.index' );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Current ID
        $current_id = Procurement::max('count_id');
        if(!$current_id) $current_id = 0;
        $data = [
            'count_id'      => $current_id + 1,
            'new_code' =>  "PR-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
        ];

        //Item List
        $inputs = Input::all();

        return view('procurement.create', compact('data', 'inputs' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProcurementRequest $request)
    {
        if ($procurement = Procurement::create($request->all())){
            // Create Approval record
            $approval = Approval::updateOrCreate(
                [
                    'procurement_id'=> $procurement->id,
                    'status'        =>  false,
                    'approval_date' =>  Carbon::now(),
                    'user_id'       =>  auth()->id(),
                ]
            );

            return response(["success"=> true, "message" => "Procurement created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Procurement!"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Procurement $procurement)
    {
       if ($procurement->approval->approved_price){
           $price = $procurement->approval->approved_price;
       } elseif ($procurement->quality) {
           $price = $procurement->quality->recommended_price;
       } else{
           $price = 0;
       }

        if ($procurement->warehouse){
            $weight = $procurement->warehouse->weight;
        } else{
            $weight = 0;
        }


       return view('procurement.show')->with(['procurement'=>$procurement, 'price'=> $price, 'weight'=>$weight]);
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
        $data = Procurement::with('supplier', 'input', 'approval');

        return DataTables::eloquent($data)
            ->addColumn('supplier', function ($row) {
                return $row->supplier->name;
            })

            ->addColumn('procurement_date', function($row){
                return Carbon::parse($row->procurement_date)->format('d-M-Y');
            })

            ->addColumn('input', function ($row) {
                return $row->input->name;
            })

            ->addColumn('approval', function ($row) {
                return $row->approval->status;
            })

            ->addColumn('action', function($row){
                $action = "";

                $action .= "<div class='btn-group dropdown'><button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Action</button> <div class='dropdown-menu'><h6 class='dropdown-header'>Actions for ".$row->code."</h6><div class='dropdown-divider'></div>";

                $action .= "<a class='dropdown-item' data-id= '" . $row->id . "' href='" . route('procurement.show', $row->id). "'  ><i class= 'fas fa-eye mr-2'></i>View Procurement</a>";

                if($row->next == ProcurementNext::ACCOUNT) {
                    $action .= "<a class='dropdown-item btnApproval' data-id= '" . $row->approval->id . "' href='#'  ><i class= 'fas fa-check mr-2'></i>Approval</a>";
                }

                if($row->approval->status){
                    $action .="<a class='dropdown-item'  href='". route('procurement.payment.index')."?procurement_id=".$row->id ."' ><i class='fa fa-money-bill mr-2'></i>Payments</a> <div class='dropdown-divider'></div>" ;
                }

                $action .="<a class='dropdown-item' data-id= '" . $row->id . "' href='edit/" .  $row->id . "'  ><i class= 'fas fa-edit mr-2'></i>Edit</a>";


                $action .=" <a class='dropdown-item' onclick='delete_invoice(".$row->id.")' href='#'><i class='far fa-trash-alt mr-2 text-danger'></i>Delete</a>";

                $action .="</div> </div>";




//                $action.="<a class='btn btn-xs btn-success' id='btnShow' href='".route('procurement.show', $row->id)."'><i class='fas fa-eye'></i></a> ";
//
//                if(Auth::user()->can('users.edit')){
//                    $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
//                }
//
//                if(Auth::user()->can('users.destroy')){
//                    $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
//                }


                return $action;
            })
            ->rawColumns([ 'action', 'status', 'approval'])
            ->make('true');
    }
}
