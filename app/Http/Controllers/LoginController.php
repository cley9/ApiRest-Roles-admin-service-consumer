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
    
        function createUser(Request $request)
        {
            $request->validate([
                  'name'=> 'required|max:105',
                'email'=> 'required|email|unique:users|max:105',
                 'password'=> 'required|confirmed|max:105',
            ]);
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->rol = '0';
                $user->avatar = '/storage/img/icons/userLogin.png';
                $user->external_auth = 'local';
                $user->save();
               $token=JWTAuth::fromUser($user);
            // $userToken = JWTAuth::guard('user')->fromUser($user);
            // $token = JWTAuth::setToken(null)->guard('user')->fromUser($user);
            // $userToken = JWTAuth::guard('user')->fromUser($user);

                 return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario creado","token"=>$token,"user"=>$user],Response::HTTP_OK);
              try {
                  // return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario creado","data"=>$user, $token]);
              } catch (\Throwable $th) {
                  // throw $th;
              }
        }
        function createUserAdmin(Request $request){
          $request->validate([
              'name'=> 'required|max:105',
            'email'=> 'required|email|unique:users|max:105',
             'password'=> 'required|confirmed|max:105',
        ]);
            $userAdmin = new User();
            $userAdmin->name = $request->name;
            $userAdmin->email = $request->email;
            $userAdmin->password = Hash::make($request->password);
            $userAdmin->rol = '4';
            $userAdmin->avatar = '/storage/img/icons/person-circle.svg';
            $userAdmin->external_auth = 'local';
            $userAdmin->save();
             $token=JWTAuth::fromUser($userAdmin);
             return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario Admin creado","token"=>$token,"userAdmin"=>$userAdmin],Response::HTTP_OK);
        }
      function loginAdmin(Request $request)
      {
          $request->validate([
              'email' => 'required|email|max:105',
              'password'=> 'required|max:105',
          ]);
          $email=$request->email;
          $credencials = [
              'email' => $email,
              'password' => $request->password,
          ];
          $validate = User::where('email', $email)->where('rol', '4')->exists();
          if (($token=JWTAuth::attempt($credencials)) && $validate) {
              $userAdmin=User::where('email',$email)->where('rol','4')->get();
              $cookie= cookie('cookie_token_agru',$token,(60*24)*7);
              return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario administrador valido","token"=>$token,"user"=>$userAdmin],Response::HTTP_OK)->withoutCookie($cookie);
          } else {
              return response()->json(["status"=>Response::HTTP_UNAUTHORIZED,'message' => 'Usuario invalido o contraseña no valida'],Response::HTTP_UNAUTHORIZED);
          }
      }
   
      function loginLocalUser(Request $request){
          // try {
               $request->validate([
                  'email' => 'required|email|max:105',
                  'password'=> 'required|max:105',
                  // 'propiedad del api'=> ['required'],
                ]);
              $email=$request->email;
              // $password=$request->password;
              $credencials = [
                  'email' => $email,
                  'password' => $request->password
              ];
              $validate = User::where('email', $email)->where('rol', '0')->exists();
              // if ($token=Auth::attempt($credencials) && $validate) {
                  if (($token=JWTAuth::attempt($credencials))&& $validate) {
                      // if ($token=JWTAuth::attempt($credencials) && $validate) {
                  // session(['name'=>'Usuario']);
                  session(['email' => $email]);
                  session(['rol' => '0']);
                  $userId = User::where('email', session()->get('email'))->where('email', session()->get('email'))->get();
                  // session(['avatar' => $userId[0]->avatar]);
                  session(['avatar' => $img = '/storage/img/icons/userLogin.png']);
                  session(['userId' => $userId[0]->id]);
                  session(['name' => $userId[0]->name]);
                  // return response(["user"=> $userId,"status"=>Response::HTTP_OK]);
                  // --cookie 
                  $cookie= cookie('cookie_token_agru',$token,(60*24)*7);
  
                 return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario valido","token"=>$token,"user"=>$userId],Response::HTTP_OK)->withoutCookie($cookie);
              //    return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario valido","token"=>$token,"user"=>$userId],Response::HTTP_OK);
              } else {
                  return response()->json(["status"=>Response::HTTP_UNAUTHORIZED,'message' => 'Usuario invalido o contraseña no valida'],Response::HTTP_UNAUTHORIZED);
                  // return response()->json(['status' => 'error', 'code' => '404', 'messange'=>'Usuario invalido o contraseña no valida']);
              }
          // } catch (\Throwable $th) {
          //     // abort();
          //     // return response()->json(['status' => 'error', 'code' => '500', 'messange'=>'Problemas con el servidor']);
          // }
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
  
  
                  $cookie= cookie('cookie_token_agru',$token,(60*24)*7);
                  return redirect()->route('vista.index')->withoutCookie($cookie)->
                  with(["data"=>["status" => Response::HTTP_OK, "message" => "Usuario valido", "token" => $token, "user" => $userId]]);
  
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
                  $cookie= cookie('cookie_token_agru',$token,(60*24)*7);
  
                  // return response()->json(["status"=>Response::HTTP_OK, "message"=>"Usuario valido","token"=>$token,"user"=>$userId],Response::HTTP_OK)->withoutCookie($cookie);
                  return redirect()->route('vista.index')->withoutCookie($cookie)->
                  with(["data"=>["status" => Response::HTTP_OK, "message" => "Usuario valido", "token" => $token, "user" => $userId]]);
                  
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
          // new 
          $cookie=Cookie::forget('cookie_token_agru');
          //  Cookie::forget('cookie_token_agru');
          // return redirect('/');
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
