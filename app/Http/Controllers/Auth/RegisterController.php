<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    private $request;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => ['resendToken', 'accountConfirm', 'changeEmail', 'changeEmailPost', 'validChangeEmail']]);
        $this->middleware('auth', ['only' => ['changeEmail', 'changeEmailPost', 'validChangeEmail']]);
        $this->middleware('isEmailConfirmed', ['only' => ['changeEmail', 'changeEmailPost', 'validChangeEmail']]);
        $this->middleware('isNotOauth', ['only' => ['changeEmail', 'changeEmailPost', 'validChangeEmail']]);
        $this->middleware('captcha', ['only' => ['register']]);
        $this->request = $request;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => array('required', 'regex:/^[A-Za-zaàéêèëîïçù0-9_[:space:]]{3,255}$/'),
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        event(new Registered($user));

        return redirect(route('home'))->with('success', trans('strings.auth_register_success'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    use CreateUser;
}
