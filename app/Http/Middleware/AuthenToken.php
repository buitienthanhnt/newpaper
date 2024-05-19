<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Thanhnt\Nan\Helper\TokenManager;

class AuthenToken
{
    protected $user;

    protected $tokenManager;

    function __construct(
        User $user,
        TokenManager $tokenManager
    ) {
        $this->user = $user;
        $this->tokenManager = $tokenManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $tokenData = (array) $this->tokenManager->getTokenData();
        // if (empty($tokenData)) {
        //     return response([
        //         'message' => 'token expire!'
        //     ], 401);
        // }

        // $tokenData = (array) $tokenData['iss'];
        // if (isset($tokenData['id']) && $id = $tokenData['id']) {
        //     $user = $this->user->find($id);
        //     Auth::setUser($user);
        // }
        return $next($request);
    }
}
