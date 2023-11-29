<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getlist(Request $request):JsonResponse
    {
        $search = $request->search;
        if ($search == '') {
            $customers = Customer::orderby('code', 'asc')->select('id', 'code', 'name')->get();
        } else {
            $customers = Customer::orderby('code', 'asc')->select('id', 'code', 'name')->where('name', 'like', '%' . $search . '%')->get();
        }


        $response = array();
        foreach ($customers as $customer) {
            $response[] = array(
                "id" => $customer->id,
                "text" => $customer->code . " | " . $customer->name,
            );
        }
        return response()->json($response);
    }
}
