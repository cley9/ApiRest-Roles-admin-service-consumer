<?php

namespace App\Http\Controllers\User_service;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|max:105',
            'email' => 'required|email|unique:users|max:105',
            'password' => 'required|confirmed|max:105',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->rol = '1';
        $user->avatar = '/storage/img/icons/userLogin.png';
        $user->external_auth = 'local';
        $user->save();
        $token = JWTAuth::fromUser($user);
        $data = [
            "name" => $user['name'],
            "email" => $user['email'],
            "avatar" => $user['avatar'],
        ];
        return response()->json(["status" => Response::HTTP_OK, "message" => "Usuario service creado", "token" => $token, "user" => $data], Response::HTTP_OK);
    }

    // function loginLocalUser(Request $request){
    //            $request->validate([
    //               'email' => 'required|email|max:105',
    //               'password'=> 'required|max:105',
    //             ]);
    //           $credencials = [
    //               'email' => $request->email,
    //               'password' => $request->password
    //           ];

    //           $validate = User::where('email', $request->email)->where('rol', '1')->exists();
    //    if (($token=JWTAuth::attempt($credencials)) && $validate) {
    //        return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario service valido","token"=>$token],Response::HTTP_OK);
    //           } else {
    //               return response()->json(["status"=>Response::HTTP_UNAUTHORIZED,'message' => 'Usuario invalido o contraseña no valida'],Response::HTTP_UNAUTHORIZED);
    //           }

    //   }
    function perfil()
    {
        return response()->json(["status" => Response::HTTP_OK, "message" => "Bien venido a tu perfil service"], Response::HTTP_OK);
    }
    function viewToken()
    {
        $token = JWTAuth::parseToken()->authenticate();
        return $token;
    }
}
