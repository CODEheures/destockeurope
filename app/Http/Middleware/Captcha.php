<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;


class Captcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $client = new Client();
        $captchaResponse = $client->post(env('GOOGLE_CAPTCHA_URL_SERVICE'),[
            'form_params' => [
                'secret' => env('GOOGLE_CAPTCHA_SECRET'),
                'response' => $request->get('g-recaptcha-response')
            ]
        ]);

        if((json_decode($captchaResponse->getBody()->getContents()))->success){
            return $next($request);
        } else {
            return redirect()->back()->withErrors(trans('strings.middleware_captcha_fail'));
        }
    }
}
