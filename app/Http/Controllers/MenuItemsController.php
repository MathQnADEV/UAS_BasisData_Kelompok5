<?php

namespace App\Http\Controllers;

use App\Models\menu_item;
use App\Models\menuItem;
use App\Models\menuItems;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuItems = menuItems::all();
        return view("menuItems.index", compact("menuItems"));
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
        // dd($request->all());
        try{
            $request->validate([
                "name" => "required|unique:menu_items",
                "category" => "required",
                "price" => "required|numeric",
                "description" => "",
                "is_available" => "boolean",
            ]);
            menuItems::create($request->all());
            return back()->with("success", "Data Menu Berhasil Ditambahkan");
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
        // dd($request->all());
        try{
            $editMenu = $request->validate([
                "name" => "required|unique:menu_items",
                "category" => "required",
                "price" => "required|numeric",
                "description" => "",
                // "is_available" => "boolean",
            ]);
            menuItems::where("item_id", $id)->update($editMenu);
            return back()->with("success", "Data Menu Berhasil Diubah");
        } catch (ValidationException $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            menuItems::where("item_id", $id)->delete();
            return back()->with("success","Data Menu Berhasil Dihapus");
        } catch (\Exception $e) {
            return back()->with("error", 'Data Menu Gagal Dapat Dihapus');
        }
    }
}
