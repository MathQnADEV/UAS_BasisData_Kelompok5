<?php

namespace App\Http\Controllers;

use App\Models\ingredients;
use App\Models\menuIngredients;
use App\Models\menuItems;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuIngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menuIngredients = menuIngredients::with(['menuItem', 'ingredient'])->get();
        $menus = menuItems::all();
        $ingredients = ingredients::all();
        return view("menuIngredients.index", compact("menuIngredients", "menus", "ingredients"));
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
        try{
            $request->validate([
                'item_id' => 'required|exists:menu_items,item_id',
                'ingredient_id' => 'required|exists:ingredients,ingredient_id',
                'qty' => 'required|numeric|min:0.01',
            ]);
            $menuIngredients = menuIngredients::where('item_id', $request->item_id)->where('ingredient_id', $request->ingredient_id)->first();
            if ($menuIngredients) {
                return back()->with('error', 'Data Resep Telah Ada');
            }
            menuIngredients::create($request->all());
            return back()->with('success', 'Data Resep Berhasil Ditambahkan');
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
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
        try{
            $request->validate([
                'item_id' => 'required|exists:menu_items,item_id',
                'ingredient_id' => 'required|exists:ingredients,ingredient_id',
                'qty' => 'required|numeric|min:0.01',
            ]);
            $menuIngredients = menuIngredients::where('item_id', $request->item_id)->where('ingredient_id', $request->ingredient_id)->first();
            if ($menuIngredients) {
                return back()->with('error', 'Data Resep Telah Ada');
            }
            menuIngredients::findOrFail($id)->update($request->all());
            return back()->with('success', 'Data Resep Berhasil Diubah');
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            menuIngredients::findOrFail($id)->delete();
            return back()->with('success','Data Resep Berhasil Dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Data Resep Tidak Dapat Dihapus');
        }
    }
}
