<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseService;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
use Google\Cloud\Storage\Connection\Rest;

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
            return redirect(route("/"));
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
        $email = $this->request->get("email");
        $password = $this->request->get("password");
        if ($email && $password) {
            $check_login = Auth::attempt(["email" => $email, "password" => $password]);
            if ($check_login) {
                $user = $this->user->where("email", "=", $email)->first();
                if ($user) {
                    Auth::login($user);
                    Cache::forget('top_menu_view');
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
            Cache::forget('top_menu_view');
            return redirect("/");
        }
        Cache::forget('top_menu_view');
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
        } else {
            dd('not has data!');
        }
    }

    /**
     * Verify password agains firebase
     * @param $email
     * @param $password
     * @return bool|string
     */
    public function verifyPassword(Request $request)
    {
        $email = $request->get('email', 'buisuphu01655@gmail.com');
        $password = $request->get('password', 'admin123');

        try {
            $response = $this->firebase->createAuth()->signInWithEmailAndPassword($email, $password);
            return $response->uid;
        } catch (FirebaseEmailExists $e) {
            logger()->info('Error login to firebase: Tried to create an already existent user');
        } catch (Exception $e) {
            logger()->error('Error login to firebase: ' . $e->getMessage());
        }
        return false;
    }

    function upLoadImage()
    {
        $firebaseFolder = 'demo/';
        $image_path = 'app/public/files/Screenshot_1664559104.png';
        $image = fopen(public_path('assets\pub_image\defaul.PNG'), 'r');
        try {
            /**
             * @var Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $bucket = $storage->getBucket();

            // upload 1 file lên store
            $response = $bucket->upload($image, ['name' => $firebaseFolder.Str::random(10).'.'.explode('.', $image_path, 2)[1]]);
            $uri = $response->info()['mediaLink'];
            dd(str_replace(Rest::DEFAULT_API_ENDPOINT.'/download/storage/v1', 'https://firebasestorage.googleapis.com/v0', $uri));
        } catch (\Throwable $th) {
            echo ($th->getMessage());
        }
    }
}
