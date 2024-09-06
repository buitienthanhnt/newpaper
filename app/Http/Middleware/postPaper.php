<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class postPaper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        echo($request->toArray()['conten']);
        dd(array_filter($request->toArray()));
        if (!$request->get("page_title")) {
            return redirect()->back()->with("error", "page title is require!");
        }
        return $next($request);
    }
}
