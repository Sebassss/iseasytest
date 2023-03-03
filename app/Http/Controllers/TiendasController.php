<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiendas;
use App\Models\Productos;


class TiendasController extends Controller
{
    //
    public function ListProductos(){

        $res = Tiendas::withCount('productos as productos')->get();
        return response()->json($res, 200);
    }

    public function ListProductosOrdenados(){

        $res = Tiendas::with('productos')
        ->get();
        return response()->json($res, 200);
    }

    public function AddTienda(Request $request){

        if(!isset($request->nombre)){
            return response()->json(['message' => 'No se pudo crear!, falta el parametro nombre'], 400);
        }
        $res = Tiendas::Create(
            [
                'nombre' => $request->nombre,
            ]
        );

        if($res){
            return response()->json(['message' => 'Tienda creada!'], 200);
        }
        return response()->json(['message' => 'No se pudo crear!'], 400);

    }

    public function DeleteTienda(Request $request){
        if(!isset($request->id)){
            return response()->json(['message' => 'No se pudo eliminar, falta el parametro id!'], 400);
        }

        $res = Tiendas::find($request->id)->delete();
        if($res){
            return response()->json(['message' => 'Tienda eliminada!'], 200);
        }else{
            return response()->json(['message' => 'No se puedo eliminar!'], 200);
        }
    }

    public function EditTienda(Request $request){
        if(!isset($request->id)){
            return response()->json(['message' => 'No se pudo editar!, falta el parametro id'], 400);
        }

        if(!isset($request->nombre)){
            return response()->json(['message' => 'No se pudo editar!, falta el parametro nombre'], 400);
        }

        $res = Tiendas::find($request->id);
        if($res){
            $res->nombre = $request->nombre;
            $res->save();
            return response()->json(['message' => 'Tienda modificada!'], 200);
        }
        return response()->json(['message' => 'No se puedo modificar!'], 200);

    }

    public function AddProductos(Request $request){

        if(!isset($request->productos)){
            return response()->json(['message' => 'por favor, ingrese un arreglo de productos, con nombre, id_tienda y cantidad'], 400);
        }

        if(!is_array($request->productos)){
            return response()->json(['message' => 'ingrese un arreglo de productos'], 200);
        }

        if(count($request->productos) > 0){

            foreach($request->productos as $key => $value){

                try{
                    $res = Productos::Create([
                        "tienda_id" => $value['tienda_id'],
                        "nombre" => $value['nombre'],
                        "cantidad" => $value['cantidad']
                    ]);
                }catch(\Exception $e){
                    return response()->json(['message' => 'No se pudo crear el/los producto/s!'], 200);
                }
            }
            return response()->json(['message' => 'Producto/s creado/s!'], 200);
        }


        return response()->json(['message' => 'ingrese un arreglo de productos con al menos 1 producto'], 200);
    }

    public function SelProductos(Request $request){

        if(!isset($request->id)){
            return response()->json(['message' => 'ingrese el id del producto'], 400);
        }

        if(!isset($request->id_tienda)){
            return response()->json(['message' => 'ingrese el id_tienda'], 400);
        }

        if(!isset($request->cantidad)){
            return response()->json(['message' => 'ingrese la cantidad a vender'], 400);
        }

        $alertBajoStock = 1;
        $res = Productos::where('id',$request->id)->first();
        if($res){

            //Si no hay stock del producto
            if($res->cantidad == 0){
                return response()->json(['message' => 'no hay stock disponible para este producto'], 400);

            }

            //Si la cantidad en stock es menos a la que se necesita vender
            if($res->cantidad < $request->cantidad){
                return response()->json(['message' => 'no hay stock suficiente para este producto'], 400);

            }

            $tmp = $res->cantidad - $request->cantidad;

            $msg = 'Stock normal '.$tmp;
            if($tmp <= $alertBajoStock){
                $msg = 'Stock critico '.$tmp;
            }

            $res->cantidad = $tmp;
            $res->save();

            return response()->json(['message' => 'producto vendido, '.$msg], 200);

        }else{
            return response()->json(['message' => 'hubo un error al vender producto'], 400);
        }
    }
}
