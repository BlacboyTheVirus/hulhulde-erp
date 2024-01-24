<?php

namespace App\Http\Controllers\Parts;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        //
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





    private function getParts(): JsonResponse
    {
        $data = Parts::where('id', '>', 0 );

        return DataTables::eloquent($data)


            ->addColumn('action', function($row){
                $action = "";

                $action .= "<a class='btn btn-xs btn-success' href='" . route('parts.edit', ['id' => $row->id]) . "'><i class='fa fa-edit'></i></a> ";

                $action .= "<a class='btn btn-xs btn-danger' href='" . route('parts.destroy', ['id' => $row->id]) . "'><i class='fa fa-trash'></i></a> ";


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }



}
