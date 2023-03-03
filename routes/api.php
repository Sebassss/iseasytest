<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendasController;


/*
    Se puede implementar un JWT como middleware
    obtener el jwt mediando endpoint login y usarlo como bearer token en las
    cabeceras para hacer peticiones seguras.
*/

//Listado de tiendas y cantidades.
Route::get('ListProductos', [TiendasController::class, 'ListProductos']);

//Lista de productos ordenados por cantidad.
Route::get('ListProductosOrdenados', [TiendasController::class, 'ListProductosOrdenados']);

//Agregar Tienda.
Route::post('AddTienda', [TiendasController::class, 'AddTienda']);

//Editar tienda.
Route::patch('EditTienda', [TiendasController::class, 'EditTienda']);

//Eliminar Tienda.
Route::delete('DeleteTienda', [TiendasController::class, 'DeleteTienda']);

//Agregar Productos.
Route::post('AddProductos', [TiendasController::class, 'AddProductos']);

//Venta de productos.
Route::post('SelProductos', [TiendasController::class, 'SelProductos']);







