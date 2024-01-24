<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }


    public function getlist(Request $request):JsonResponse
    {
        $search = $request->search;
        if ($search == '') {
            $suppliers = Supplier::orderby('code', 'asc')->select('id', 'code', 'name')->get();
        } else {
            $suppliers = Supplier::orderby('code', 'asc')->select('id', 'code', 'name')->where('name', 'like', '%' . $search . '%')->get();
        }


        $response = array();
        foreach ($suppliers as $supplier) {
            $response[] = array(
                "id" => $supplier->id,
                "text" => $supplier->code . " | " . $supplier->name,
            );
        }
        return response()->json($response);
        //return json_encode($response);
    }
}
