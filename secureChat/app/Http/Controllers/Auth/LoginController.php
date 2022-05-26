<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

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
    
      /**
     * Write code on Method
     *
     * @return response()
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            [$privateKey, $publicKey]  = (new KeyPair())->generate();
            session(['private_key'=>$privateKey]);
            Message::updateKey($publicKey); 
            return redirect()->route('/home');
        }
    
        return redirect("/")->withSuccess('Oppes! You have entered invalid credentials');
    }
    public function logout(Request $request) {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        Message::setConnected(false,$id_sender);
        Message::updateKey(null);
        Auth::logout();
        return redirect('/home');
      }
}
