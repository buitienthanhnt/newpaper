<?php
namespace App\Api;

use App\Api\Data\Response;
use App\Api\Data\User\UserInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserApi
{
    protected $request;

    protected $user;
    protected $userRepository;

    protected $response;

    function __construct(
        Request $request,
        User $user,
        UserRepository $userRepository,
        Response $response
    )
    {
        $this->request = $request;
        $this->user = $user;
        $this->response = $response;
        $this->userRepository = $userRepository;
    }

    function getUserInfo() {
        $user = Auth::user();
        if ($user) {
            /**
             * @var UserInfo $userInfo
             */
            $userInfo = $this->userRepository->convertUserInfo($user);
            return  $this->response->setResponse($userInfo);
            return response($userInfo, 200);
        }
        return response([
            'message' => 'you are not logined, please login to continue process.'
        ], 402);
    }

    function logIn() {

        $request = $this->request;
        $email = $request->get('email');
        $password = $request->get('password');
        if (!($email && $password)) {
            return response([
                "message" => "invalid email or password"
            ], 400);
        }
        if (Auth::check()) {
            return response([
                "message" => "người dùng đã đăng nhập, không thể thực hiện thêm!"
            ], 403);
        }

        if (Auth::attempt([
            'email' => $email,
            "password" => $password
        ])) {
            $user = $this->user->where("email", $email)->first();
            Auth::login($user);
            $userData = $user->toArray();
            $userData["sid"] = Session::getId();

            return $this->response->setResponse($this->userRepository->convertUserAuth($user));
        }
        return response([
            'message' => "login fail, error email or password",
        ], 400);
    }
}
