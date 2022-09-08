<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function username() {
        return 'username';
    }

 //   protected function sendLoginResponse(Request $request)
  //  {
        // $user = User::where('username', $request->username)->firstOrFail();
        // $token = $user->createToken('auth_token')->plainTextToken;

        // $user = User::where('username', $request->username)->firstOrFail();
        // $user->createToken('auth_token')->accessToken;



      //  return response()->json(compact('token'));


      //  return $this->authenticated();
  //  }


    public function authenticated()
    {
        if (auth()->user()->divisi->id == 24) {
            return redirect('/ppic');
        } else if (auth()->user()->divisi->id == 15) {
            return redirect('/logistik/dashboard');
        } else if (auth()->user()->divisi->id == 3) {
            return redirect('/manager-teknik');
        } else if (auth()->user()->divisi->id == 23) {
            return redirect('/qc/dashboard');
        } else if (auth()->user()->divisi->id == 26 || auth()->user()->divisi->id == 8 || auth()->user()->divisi->id == 32) {
            return redirect('/penjualan/dashboard');
        } else if (auth()->user()->divisi->id == 31 || auth()->user()->divisi->id == 13) {
            return redirect('/gbj/dashboard');
        } else if (auth()->user()->divisi->id == 17) {
            return redirect('/produksi/dashboard');
        } else if (auth()->user()->divisi->id == 12) {
            return redirect('/gk/dashboard');
        } else if (auth()->user()->divisi->id == 9) {
            return redirect('/dc/dashboard');
        } else if (auth()->user()->divisi->id == 2) {
            return redirect('/direksi/dashboard');
        } else if (auth()->user()->divisi->id == 28) {
            return redirect('/kesehatan/dashboard');
        }
        return redirect('/home');
    }


}
