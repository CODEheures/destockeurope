<?php

namespace App\Http\Controllers;



use Illuminate\Contracts\Auth\Guard;

class UserController extends Controller
{

    private $auth;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('auth');
        $this->auth = $auth;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->auth->user();
        return view('user.account', compact('user'));
    }
}
