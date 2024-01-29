<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
// use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Support\Facades\Cookie;
// use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
// use JWTAuth;
use Str;
use Illuminate\Support\Facades\Mail;


// /**
// * @OA\Info(
// *             title="Loguin para user y admin", 
// *             version="1.0",
// *             description="logueo de user y admin"
// * )
// *
// * @OA\Server(url="http://127.0.0.1:8000")
// */
class LoginController extends Controller
{
    function loginLocalUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:105',
            'password' => 'required|max:105',
        ]);
        $credencials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if ($token = JWTAuth::attempt($credencials)) {
            return response()->json(["status" => Response::HTTP_OK, "message" => "Usuario valido", "token" => $token], Response::HTTP_OK);
        } else {
            return response()->json(["status" => Response::HTTP_UNAUTHORIZED, 'message' => 'Usuario invalido o contraseña no valida'], Response::HTTP_UNAUTHORIZED);
        }
    }
    function loginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    function callback()
    {
        // $user= Socialite::driver('google')->stateless()->user();
        try {
            $google_user = Socialite::driver('google')->user();
            $user = User::where('email', $google_user->email)->first(); //entrega un true o false
            if ($user) {
                // Auth::login($user);
                // $userId = User::where('name', session()->get('name'))->where('email', session()->get('email'))->get();
                // session(['userId' => $userId[0]->id]); // [0] this para quitar el [ {json }] y solo vea { json} para poder acceder
                $userId = User::where('email', $google_user->email)->where('email', $google_user->email)->get();
                $credencials = [
                    'email' => $google_user->email,
                    'password' => $google_user->email
                ];
                // $token=JWTAuth::attempt($credencials);
                $token = JWTAuth::setToken(null)->guard('user')->attempt($credencials);
                // $token = auth()->guard('user')->attempt($user); // Para el rol user
                $cookie = cookie('cookie_token_agru', $token, (60 * 24) * 7);
                return redirect()->route('vista.index')->withoutCookie($cookie)->with(["data" => ["status" => Response::HTTP_OK, "message" => "Usuario valido", "token" => $token, "user" => $userId]]);
                // return redirect()->route('vista.index');
            } else {
                $new_user = new User();
                $new_user->name = $google_user->name;
                $new_user->email = $google_user->email;
                $new_user->password = Hash::make($google_user->email); // pendiente de cambio por el token de google 
                $new_user->rol = '0';
                $new_user->avatar = $google_user->avatar;
                $new_user->external_auth = 'google';
                $new_user->save();
                $token = auth()->guard('user')->attempt($user); // Para el rol user
                $userId = User::where('email', $google_user->email)->where('email', $google_user->email)->get();
                $cookie = cookie('cookie_token_agru', $token, (60 * 24) * 7);
                // return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario valido","token"=>$token,"user"=>$userId],Response::HTTP_OK)->withoutCookie($cookie);
                return redirect()->route('vista.index')->withoutCookie($cookie)->with(["data" => ["status" => Response::HTTP_OK, "message" => "Usuario valido", "token" => $token, "user" => $userId]]);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function indexHome()
    {
        return redirect('vista.index');
    }

    function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $cookie = Cookie::forget('cookie_token_agru');
        return redirect('/')->withCookie($cookie); // para vaciar el cookie o eliminar
        // return response(["da"=>200, 200])->withCookie($cookie);
    }
    // validate user email
    function validarUser($emailExists)
    {
        $userConsutal = User::where('email', $emailExists)->exists();
        if ($userConsutal) {
            return "true";
        } else {
            return "false";
        }
    }
}
