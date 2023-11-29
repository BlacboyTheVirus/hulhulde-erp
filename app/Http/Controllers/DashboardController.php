<?php

namespace App\Http\Controllers;

use App\Models\Input;
use App\Models\Procurement\Payment;
use App\Models\Procurement\Procurement;
use App\Models\Procurement\Warehouse;
use App\Models\Product;
use App\Models\Production\Production;
use App\Models\Sales\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paddy_quantity = Input::where('name', 'Paddy')->pluck('quantity')->first();
        $paddy_procured = Warehouse::sum('weight');
        $products = Product::all();
        $payments =Payment::sum('amount');

        $procurements = Procurement::count();
        $productions = Production::count();

        $invoices_count = Invoice::count();
        $invoices_amount = Invoice::sum('grand_total');


        $processed_paddy = \App\Models\Production\Warehouse::sum('weight');

        return view('dashboard', compact('paddy_procured','paddy_quantity', 'procurements', 'products', 'payments','productions', 'processed_paddy', 'invoices_count', 'invoices_amount') );
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
}
