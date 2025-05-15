<?php

namespace App\Http\Controllers;

use App\Models\ingredients;
use App\Models\menuIngredients;
use App\Models\menuItems;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = ingredients::all();
        return view("ingredients.index", compact("ingredients"));
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
                "name" => "required|unique:ingredients",
                "unit" => "required",
                "current_stock" => "required|numeric|min:0",
            ]);
            ingredients::create($request->all());
            return back()->with("success", "Data Bahan Berhasil Ditambahkan");
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
                "name" => "required|unique:ingredients,name," . $id . ",ingredient_id",
                "unit" => "required",
                "current_stock" => "required|numeric|min:0",
            ]);

            $ingredients = ingredients::find($id);
            $ingredients->update($request->all());

            // Ambil semua menu_item yang memakai bahan ini
            $menuIngredients = menuIngredients::where('ingredient_id', $id)->get();

            foreach ($menuIngredients as $menuIngredient) {
                $menuItemId = $menuIngredient->item_id;

                // Cek apakah semua bahan untuk menu ini cukup stok-nya
                $allIngredients = menuIngredients::where('item_id', $menuItemId)->get();

                $isAvailable = true;

                foreach ($allIngredients as $ing) {
                    $ingredientStock = ingredients::find($ing->ingredient_id);
                    if (!$ingredientStock || $ingredientStock->current_stock < $ing->qty) {
                        $isAvailable = false;
                        break;
                    }
                }

                // Update status ketersediaan menu
                $menuItem = menuItems::find($menuItemId);
                if ($menuItem) {
                    $menuItem->is_available = $isAvailable;
                    $menuItem->save();
                }
            }

            return back()->with("success", "Data Bahan Berhasil Diubah");
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
            $ingredients = ingredients::find($id);
            $ingredients->delete();
            return back()->with("success", "Data Bahan Berhasil Dihapus");
        } catch (\Exception $e) {
            return back()->with("error", 'Bahan tidak dapat dihapus karena masih digunakan pada menu.');
        }
    }
}
