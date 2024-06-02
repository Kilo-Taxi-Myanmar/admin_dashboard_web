<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fees = Fee::all();
        return view('backend.fee.index', compact('fees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:fees,type',
            'amount' => 'required|numeric|min:0',
        ]);

        Fee::create($validatedData);

        return redirect()->route('fee.index')->with('success', 'Fee created successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fee  $fees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fee $fee)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $fee->update($validatedData);

        return redirect()->route('fee.index')->with('success', 'Fee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fee  $fees
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();

        return redirect()->route('fee.index')->with('success', 'Fee deleted successfully!');
    }
}
