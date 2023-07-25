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
     * SignOut
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->check_login()) {
            $this->checPermission($request);
            return $next($request);
        }
        return redirect()->route("admin_login")->with("error", "please login first!");
    }

    public function check_login() : bool {
        if (!Session::isStarted()) {
            Session::start();
        }
        return Session::has("admin_user");
    }

    /**
     *  @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|void
     */
    public function checPermission(Request $request) {
        // dd($request->route()->getAction()["controller"]);
    }
}
