<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/productos');


Route::resource('productos', ProductoController::class)
    ->except(['show'])
    ->parameters(['productos' => 'id']);