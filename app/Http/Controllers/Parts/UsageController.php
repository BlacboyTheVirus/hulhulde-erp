<?php

namespace App\Http\Controllers\Parts;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsageRequest;
use App\Models\Parts\Parts;
use App\Models\Parts\Usages;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class UsageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            //Datatables for All Parts
            return  $this->getUsages();
        }

        return view('parts.usage.index' );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsageRequest $request)
    {
        $part  = Parts::find($request->parts_id);
        if ($usage = Usages::create($request->all())){
            // Update Parts Record
            $new_quantity = $part->quantity - $request->quantity;
            $part->update(['quantity' => $new_quantity]);

            return response(["success"=> true, "message" => "Usage created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Usage!"], 200);
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


    private function getUsages(): JsonResponse
    {
        $data = Usages::where('id', '>', 0 );

        return DataTables::eloquent($data)
            ->addColumn('code', function ($row) {
                return $row->parts->code;
            })
            ->addColumn('name', function ($row) {
                return $row->parts->name;
            })

            ->addColumn('action', function($row){
                $action = "";

                $action .= "<a class='btn btn-xs btn-success' href='" . route('parts.usage.edit',  $row->id) . "'><i class='fa fa-edit'></i></a> ";

                $action .= "<a class='btn btn-xs btn-danger' href='" . route('parts.usage.destroy', $row->id) . "'><i class='fa fa-trash'></i></a> ";


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }
}
