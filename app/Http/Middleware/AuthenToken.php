<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Thanhnt\Nan\Helper\TokenManager;

class AuthenToken
{
    protected $user;

    protected $tokenManager;

    private $passUrl = [
        'api/getcategorytop',
        'api/mostviewdetail',
        'api/share/mostPopulator',
        'api/share/trending',
        'api/share/likeMost',
        'api/test/submit'
    ];

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
        $path = $request->getPathInfo();
        if (!in_array(trim($path, '/'), $this->passUrl)) {
            $tokenData = (array) $this->tokenManager->getTokenData();
            if (empty($tokenData)) {
                return response([
                    'message' => 'token expire!'
                ], 401);
            }
            $tokenData = (array) $tokenData['iss'];

            if (isset($tokenData['id'])) {
                Auth::setUser($this->user->find($tokenData['id']) ?? null);
            }

            if (isset($tokenData['sid'])) {
                if (!Session::isStarted()) {
                    Session::start();
                }
                Session::setId($tokenData['sid']);
                Session::start();
            }
        }
        return $next($request);
    }
}
