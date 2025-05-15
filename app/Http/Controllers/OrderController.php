<?php

namespace App\Http\Controllers;

use App\Models\customers;
use App\Models\employees;
use App\Models\ingredients;
use App\Models\menuIngredients;
use App\Models\menuItems;
use App\Models\orderItems;
use App\Models\orders;
use App\Models\ViewOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewOrders = ViewOrder::all();
        $menuItems = menuItems::where('is_available', true)->get();
        $kategoriList = DB::table('menu_items')->select('category')->distinct()->pluck('category');
        $customers = customers::all();
        $employees = employees::all();
        return view('orders.index', compact('menuItems', 'customers', 'employees', 'viewOrders', 'kategoriList'));
    }

    public function getMenuByCategory(Request $request)
    {
        $kategori = $request->input('kategori');

        try {
            $menus = DB::select("CALL menuCategory(?)", [$kategori]);
            return response()->json($menus);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
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
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,customer_id',
                'employee_id' => 'required|exists:employees,employee_id',
                'order_date' => 'required|date',
                'items' => 'required|array',
                'items.*.item_id' => 'required|exists:menu_items,item_id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.special_req' => 'nullable|string'
            ]);

            $insufficientMenus = [];

            foreach ($request->items as $item) {
                $menuItem = menuItems::find($item['item_id']);
                $menuIngredients = menuIngredients::where('item_id', $menuItem->item_id)->get();

                foreach ($menuIngredients as $ingredientInfo) {
                    $ingredient = ingredients::find($ingredientInfo->ingredient_id);
                    $requiredQty = $ingredientInfo->qty * $item['qty'];

                    if ($ingredient->current_stock < $requiredQty) {
                        $insufficientMenus[] = $menuItem;
                        // Tandai menu tidak tersedia
                        $menuItem->is_available = false;
                        $menuItem->save();
                        break; // stop cek bahan menu ini, sudah tidak cukup
                    }
                }
            }

            if (count($insufficientMenus) > 0) {
                $menuNames = implode(', ', array_map(function ($menu) {
                    return $menu->name;
                }, $insufficientMenus));

                return redirect()->route('orders.index')->with('error', 'Stok tidak mencukupi untuk menu: ' . $menuNames);
            }

            $total = 0;
            foreach ($request->items as $item) {
                $menuItem = menuItems::find($item['item_id']);
                $total += $menuItem->price * $item['qty'];
            }

            $order = orders::create([
                'customer_id' => $request->customer_id,
                'employee_id' => $request->employee_id,
                'order_date' => $request->order_date,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => 'completed'
            ]);

            foreach ($request->items as $item) {
                $menuItem = menuItems::find($item['item_id']);
                orderItems::create([
                    'order_id' => $order->order_id,
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'price_at_order' => $menuItem->price,
                    'special_req' => $item['special_req'] ?? null,
                ]);
            }

            return redirect()->route('orders.index')->with('success', 'Order telah terbuat');
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orderSummary = orders::with(['customer', 'employee', 'orderItems.menuItem'])->findOrFail($id);
        return response()->json([$orderSummary]);
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
