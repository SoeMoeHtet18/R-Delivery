<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReCaptcha\ReCaptcha;
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
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        
        return \redirect('/users');
    }

    protected function validateLogin(Request $request)
    {   

        $vars = array(
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            "response" => $request->input('recaptcha_v3')
        );
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        dd($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $encoded_response = curl_exec($ch);
        $response = json_decode($encoded_response, true);
        curl_close($ch);
        if($response['success'] && $response['action'] == 'login' && $response['score']>0.5) {
            //continue basic login logic
            $this->validate($request, [
                'phone_number' => 'required|string',
                'password' => 'required|string',
            ]);
        } else {
            //then probably this is a bot
            //you can do your logic here pass it or deny or do something special
            //score check value of 0.5 you can set which you want form 0 to 1
            //score 1 is probably human score 0 is probably bot
            dd("I Am Robotttt :D");
        }

        
       
    }

    protected function credentials(Request $request)
    {
        return $request->only('phone_number', 'password');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'phone_number' => [trans('auth.failed')],
        ]);
    }
}
