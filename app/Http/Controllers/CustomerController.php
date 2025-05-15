<?php

namespace App\Http\Controllers;

use App\Models\customers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = customers::all();
        return view("customer.index", compact("customers"));
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
        try {
            $request->validate([
                "name" => "required|unique:customers",
            ]);
            customers::create($request->all());
            return back()->with("success", "Data Pelanggan Berhasil Ditambahkan");
        } catch (ValidationException $e) {
            return back()->with("error", $e->getMessage());
        }
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
        try {
            $request->validate([
                "name" => "required|unique:customers,name," . $id . ",customer_id",
            ]);
            $customer = customers::find($id);
            $customer->update([
                'name' => $request->name,
            ]);
            return back()->with('success', 'Data Pelanggan Berhasil Diubah');
        } catch (ValidationException $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = customers::find($id);
            $customer->delete();
            return back()->with('success', 'Data Pelanggan Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Data Pelanggan Gagal Dihapus');
        }
    }
}
