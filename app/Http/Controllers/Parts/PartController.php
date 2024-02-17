<?php

namespace App\Http\Controllers\Parts;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartRequest;
use App\Models\Parts\Parts;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

       if ($request->ajax()){
            //Datatables for All Parts
            return  $this->getParts();
        }

        //Current ID
        $current_id = Parts::max('count_id');
        if(!$current_id) $current_id = 0;
        $data = [
            'count_id'      => $current_id + 1,
            'new_code'      =>  "PX-" . str_pad($current_id + 1, 4, "0", STR_PAD_LEFT),
        ];

        return view('parts.index' )->with(compact('data' ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


//        // return (compact ('data', 'invoice', 'customer'));
//        return view('parts.create', compact('data' ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartRequest $request)
    {
        if ($part = Parts::create($request->all())){

            return response(["success"=> true, "message" => "Part created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Part!"], 200);
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
    public function edit(Parts $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parts $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parts $id)
    {
        //
    }




    public function getlist(Request $request):JsonResponse
    {
        $search = $request->search;
        if ($search == '') {
            $parts = Parts::orderby('code', 'asc')->select('id', 'code', 'name', 'quantity', 'unit')->get();
        } else {
            $parts = Parts::orderby('code', 'asc')->select('id', 'code', 'name', 'quantity', 'unit')->where('name', 'like', '%' . $search . '%')->get();
        }


        $response = array();
        foreach ($parts as $part) {
            $response[] = array(
                "id" => $part->id,
                "text" => $part->code . " | " . $part->name ." | ". (0.01 * $part->quantity*100).' '.$part->unit ,
            );
        }
        return response()->json($response);
        //return json_encode($response);
    }



    private function getParts(): JsonResponse
    {
        $data = Parts::where('id', '>', 0 );

        return DataTables::eloquent($data)

            ->addColumn('restock_level', function ($row) {
                if ($row->quantity <= $row->restock_level ) {
                    $but = "<span class='badge bg-danger'> Restock </span> ";
                    return $but;
                } else {
                    $but = "<span class='badge bg-success'> Available </span> ";
                    return $but;
                }
            })

            ->addColumn('action', function($row){
                $action = "";

                $action .= "<a class='btn btn-xs btn-success' href='" . route('parts.edit',  $row->id) . "'><i class='fa fa-edit'></i></a> ";

                $action .= "<a class='btn btn-xs btn-danger' href='" . route('parts.destroy', $row->id) . "'><i class='fa fa-trash'></i></a> ";


                return $action;
            })
            ->rawColumns([ 'action', 'restock_level'])
            ->make('true');
    }



}
