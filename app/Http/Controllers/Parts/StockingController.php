<?php

namespace App\Http\Controllers\Parts;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockingRequest;
use App\Models\Parts\Parts;
use App\Models\Parts\Stockings;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;

class StockingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()){
            //Datatables for All Parts
            return  $this->getStockings();
        }

        return view('parts.stockings.index' );

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
    public function store(StoreStockingRequest $request)
    {
        $part  = Parts::find($request->parts_id);
        if ($stocking = Stockings::create($request->all())){

            // Update Parts Record
            $new_quantity = $part->quantity + $request->quantity;
            $part->update(['quantity' => $new_quantity]);

            return response(["success"=> true, "message" => "Stocking created successfully."], 200);
        }
        return response(["success"=> false, "message" => "Error creating Stocking!"], 200);
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


    private function getStockings(): JsonResponse
    {
        $data = Stockings::where('id', '>', 0 );

        return DataTables::eloquent($data)
            ->addColumn('code', function ($row) {
                return $row->parts->code;
            })
            ->addColumn('name', function ($row) {
                return $row->parts->name;
            })



            ->addColumn('action', function($row){
                $action = "";

                $action .= "<a class='btn btn-xs btn-success' href='" . route('parts.stocking.edit',  $row->id) . "'><i class='fa fa-edit'></i></a> ";

                $action .= "<a class='btn btn-xs btn-danger' href='" . route('parts.stocking.destroy', $row->id) . "'><i class='fa fa-trash'></i></a> ";


                return $action;
            })
            ->rawColumns([ 'action'])
            ->make('true');
    }
}
