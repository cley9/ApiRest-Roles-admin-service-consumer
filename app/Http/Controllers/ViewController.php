<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
* @OA\Info(
*             title="Loguin para user y admin", 
*             version="1.0",
*             description="logueo de user y admin"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/
class ViewController extends Controller
{
     /**
     * Título que define lo que hará esta URI
     * 
     * @OA\Get(
     *     path="/token",
     *     tags={"Autenticación"},
     *     summary="Obtener token CSRF",
     *     description="Obtiene un token CSRF para la autenticación",
     *     @OA\Response(
     *         response=200,
     *         description="Token CSRF generado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJpdiI6IlwvXC9Icl..."
     *             )
     *         )
     *     )
     * )
     */
    function token(){
        try {
            return response()->json(['token'=>csrf_token()]);
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
