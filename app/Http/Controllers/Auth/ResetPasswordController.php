<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

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

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['changePasswordForm', 'change']]);
        $this->middleware('isNotOauth', ['only' => ['changePasswordForm', 'change']]);
    }

    public function sendResetResponse($response)
    {
        return redirect(route('home'))
            ->with('success', 'Votre mot de passe est réinitialisé');
    }

    public function changePasswordForm()
    {
        return view('auth.passwords.change');
    }

    /**
     * @param Request $request
     * @return ResetPasswordController|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function change(Request $request)
    {
        $this->validate($request, $this->rulesChange(), $this->validationErrorMessages());
        if(auth()->check()){
            $user = auth()->user();
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect(route('home'))->with('success', trans('strings.auth_passwordReset_success'));
        }
        return redirect(route('home'))->withErrors(trans('strings.view_all_error_patch_message'));
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:'.config('db_limits.users.minPasswordLength'),
        ];
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rulesChange()
    {
        return [
            'password' => 'required|confirmed|min:'.config('db_limits.users.minPasswordLength'),
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.required' => trans('strings.request_password_required'),
            'password.confirmed' => trans('strings.request_password_confirmed'),
            'password.min' => trans('strings.request_password_min', ['chars' => config('db_limits.users.minPasswordLength')]),
        ];
    }
}
