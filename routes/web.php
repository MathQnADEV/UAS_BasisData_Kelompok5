<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\MenuIngredientsController;
use App\Http\Controllers\MenuItemsController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('customer',CustomerController::class);
Route::resource('employee',EmployeeController::class);
Route::resource('menuItems',MenuItemsController::class);
Route::resource('ingredients',IngredientsController::class);
Route::resource('menuIngredient', MenuIngredientsController::class);
Route::resource('orders', OrderController::class);
Route::post('/get-menu-by-category', [OrderController::class, 'getMenuByCategory']);
