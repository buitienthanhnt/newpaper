<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogin
{
    /**
     * Handle an incoming request.
     * Sign
                        Out
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        if ($this->check_login()) {
            return $next($request);
        }
        return redirect()->route("admin_login")->with("error", "please login first!");
    }

    function check_login() : bool {
        if (!Session::isStarted()) {
            Session::start();
        }
        return Session::has("admin_user");
    }
}
