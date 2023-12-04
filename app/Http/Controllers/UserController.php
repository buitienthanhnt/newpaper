<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;

class UserController extends BaseController
{
    //
    protected $request;
    protected $user;

    public function __construct(
        Request $request,
        User $user,
        FirebaseService $firebaseService
    ) {
        $this->request = $request;
        $this->user = $user;
        parent::__construct($firebaseService);
    }

    public function loginPage()
    {
        if (Auth::check()) {
            return redirect(route("user_detail"));
        } else {
            return view("frontend/templates/login");
        }
    }

    public function createAccount()
    {
        return view("frontend/templates/new_account");
    }

    public function accountAdd()
    {
        if ($this->request->get("user_email") && $this->request->get("password") && $this->request->get("user_name")) {
            $verify_email = $this->user->where("email", "=", $this->request->get("user_email"))->get();
            if ($verify_email->count()) {
                return redirect()->back(302, "the email exist");
            } else {
                $user = $this->user;
                $user->email = $this->request->get("user_email");
                $user->password = Hash::make($this->request->get("password"));
                $user->name = $this->request->get("user_name");
                $user->save();
                return redirect("/");
            }
        }
        // return "accountAdd";
    }

    public function login()
    {
        if ($email = $this->request->get("email") && $password = $this->request->get("password")) {
            $check_login = Auth::attempt(["email" => $this->request->get("email"), "password" => $this->request->get("password")]);
            if ($check_login) {
                $user = $this->user->where("email", "=", $email)->first();
                if ($user) {
                    Auth::login($user);
                }
            }
        }
        return redirect()->back();
    }

    public function detail()
    {
        if (Auth::check()) {
            $user = Auth::user();
            dd($user);
            return view("frontend/templates/userDetail");
        } else {
            return redirect(route("user_login"));
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect("/");
        }
        return redirect()->back(302);
    }

    public function authenticate(Request $request)
    {
        // $credentials = $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('dashboard');
        // }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    function addFireBaseData(Request $request)
    {
        /**
         * khong lay field conten
         */
        $paper = Paper::find($request->get('paper_id', 1))->makeHidden(['conten'])->toArray();
        if ($paper) {
            try {
                $userRef = $this->database->getReference('/newpaper/papers');
                $userRef->push($paper);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }
            $snapshot = $userRef->getSnapshot();
            dd($snapshot->getValue());
        }else{
            dd('not has data!');
        }
    }
}
