<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class SocialiteController extends Controller
{

    use RegistersUsers;
    use CreateUser;

    private $isNewOauthUser = false;
    private $request;
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');
        $this->request = $request;
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        if($this->request->has('subscribeNewsLetter') && $this->request->subscribeNewsLetter == true){
            session(['subscribeNewsLetter' => true]);
        }
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect(route('login'))->withErrors('Probleme de connexion avec ' . $provider);
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        if(is_a($authUser, RedirectResponse::class)) {
            return $authUser;
        } else {
            Auth::login($authUser, true);

//            if ($authUser->role == 'admin') {
//                $route = route('admin.monitor.index');
//            } else {
//                $route = route('customer.monitor.index');
//            }

            $route = ($this->redirectTo);

            if($this->isNewOauthUser) {
                return redirect($route)
                    ->with('success', trans('strings.auth_register_oauth_success',['appname' => config('app.name'), 'username' => $authUser->name]) );
            } else {
                return redirect($route);
            }
        }
    }
}
