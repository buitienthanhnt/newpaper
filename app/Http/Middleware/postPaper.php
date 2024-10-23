<?php

namespace App\Http\Middleware;

use App\Models\PaperInterface;
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
        if (!$request->get(PaperInterface::ATTR_TITLE)) {
            return redirect()->back()->with("error", "page title is require!");
        }
        return $next($request);
    }
}
