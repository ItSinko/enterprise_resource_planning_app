<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    // /**
    //  * Where to redirect users after resetting their password.
    //  *
    //  * @var string
    //  */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function update_pwd(Request $request)
    {


        $validator = Validator::make($request->all(),  [
            'pwd_lama' => 'required|string',
            'password' => 'required|confirmed|min:8|string'
        ]);

        $auth = Auth::user();
        if ($validator->fails()) {
            return response()->json(['data' => 'cek']);
        } else {

            if (!Hash::check($request->get('pwd_lama'), $auth->password)) {
                return response()->json(['data' => 'invalid']);
            }


            if (strcmp($request->get('pwd_lama'), $request->password) == 0) {
                return response()->json(['data' => 'same']);
            }


            $user =  User::find($auth->id);
            $user->password =  Hash::make($request->password);
            $user->save();
            return response()->json(['data' => 'success']);
        }
    }
}
