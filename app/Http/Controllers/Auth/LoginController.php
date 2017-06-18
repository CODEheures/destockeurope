<?php

namespace App\Http\Controllers\Auth;

use App\Common\PicturesManager;
use App\Common\PrivilegesUtils;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        (new PicturesManager())->purgeSessionLocalTempo();

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        $request->session()->flash('clear', true);

        return redirect(route('home'));
    }

    protected function authenticated(Request $request, $user)
    {
        $this->redirectTo = PrivilegesUtils::mustBeRedirectToOnLogin($user);
    }
}
