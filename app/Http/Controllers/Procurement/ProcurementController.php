<?php /** @noinspection ALL */

namespace App\Http\Controllers\Procurement;

use App\Http\Requests\StoreProcurementRequest;
use App\Models\Input;
use App\Models\Procurement;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;


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
        if (Procurement::create($request->all())){
            return response(["success"=> true, "message" => "Procurement created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Procurement!"], 200);
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
        $data = Procurement::with('supplier', 'input');

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

                $action.="<a class='btn btn-xs btn-success' id='btnShow' href='".route('users.show', $row->id)."'><i class='fas fa-eye'></i></a> ";

                if(Auth::user()->can('users.edit')){
                    $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
                }

                if(Auth::user()->can('users.destroy')){
                    $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                }


                return $action;
            })
            ->rawColumns([ 'action', 'status'])
            ->make('true');
    }
}
