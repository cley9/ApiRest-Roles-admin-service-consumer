<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    function createUserAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|max:105',
            'email' => 'required|email|unique:users|max:105',
            'password' => 'required|confirmed|max:105',
        ]);
        $userAdmin = new User();
        $userAdmin->name = $request->name;
        $userAdmin->email = $request->email;
        $userAdmin->password = Hash::make($request->password);
        $userAdmin->rol = '4';
        $userAdmin->avatar = '/storage/img/icons/person-circle.svg';
        $userAdmin->external_auth = 'local';
        $userAdmin->save();
        $token = JWTAuth::fromUser($userAdmin);
        $data = [
            "name" => $userAdmin['name'],
            "email" => $userAdmin['email'],
            "avatar" => $userAdmin['avatar'],
        ];
        return response()->json(["status" => Response::HTTP_OK, "message" => "Usuario Admin creado", "token" => $token, "userAdmin" => $data], Response::HTTP_OK);
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:105',
            'password' => 'required|max:105',
        ]);
        $credencials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $validate = User::where('email', $request->email)->where('rol', '4')->exists();
        if (($token = JWTAuth::attempt($credencials)) && $validate) {
            return response()->json(["status" => Response::HTTP_OK, "message" => "Usuario admin valido", "token" => $token], Response::HTTP_OK);
        } else {
            return response()->json(["status" => Response::HTTP_UNAUTHORIZED, 'message' => 'Usuario invalido o contraseña no valida'], Response::HTTP_UNAUTHORIZED);
        }
    }
    function perfil()
    {
        return response()->json(["status" => Response::HTTP_OK, "message" => "Bien venido a tu perfil admin"], Response::HTTP_OK);
    }
    function viewToken()
    {
        $token = JWTAuth::parseToken()->authenticate();
        return $token;
        //  return "cley";   
    }
}
