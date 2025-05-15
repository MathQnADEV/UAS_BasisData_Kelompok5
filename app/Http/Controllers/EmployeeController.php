<?php

namespace App\Http\Controllers;

use App\Models\employees;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = employees::all();
        return view('employee.index', compact("employees"));
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
                "name" => "required|unique:employees",
                "position" => "required",
            ]);
            employees::create($request->all());
            return back()->with("success", "Data Pegawai Berhasil Ditambahkan");
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
                "name" => "required|unique:employees,name," . $id . ",employee_id",
                "position" => "required",
            ]);
            $employee = employees::find($id);
            $employee->update($request->all());
            return back()->with("success", "Data Pegawai Berhasil Diubah");
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
            $employee = employees::find($id);
            $employee->delete();
            return back()->with("success", "Data Pegawai Berhasil Dihapus");
        } catch (\Exception $e) {
            return back()->with("error", 'Data Pegawai Tidak Dapat Dihapus');
        }
    }
}
