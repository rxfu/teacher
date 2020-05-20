<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class CASAuth
{

    protected $auth;
    protected $cas;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->cas = app('cas');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( $this->cas->checkAuthentication() )
        {
            $user = User::whereGh($this->cas->user())->first();

            if (!$user) {
                return redirect()->route('error', ['message' => '此用户在教务处不存在']);
            }

            if (!Auth::check()) {
                Auth::loginUsingId($user->jsgh);
            }

            // Store the user credentials in a Laravel managed session
            session()->put('cas_user', $this->cas->user());
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            $this->cas->authenticate();
        }

        return $next($request);
    }
}
