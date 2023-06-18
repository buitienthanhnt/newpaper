<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    protected $adminUser;

    public function __construct(
        AdminUser $adminUser
    )
    {
        $this->adminUser = $adminUser;
    }

    public function adminLogin()
    {
        if ($this->get_user_admin()) {
            return redirect()->route("admin");
        }
        return view("adminhtml/templates/adminUser/adminLogin");
    }

    public function loginPost(Request $request)
    {
        if ($ad = $this->get_user_admin()) {
            dd($ad->toArray());
        }
        if ($request->get("admin_user") && $request->get("admin_password")) {
            $adminUser = $this->adminUser->where("name", "=", $request->get("admin_user"))->get()->first();
            if (Hash::check($request->get("admin_password"), $adminUser->password)) {
                $this->admin_login($adminUser);
            }else {
                dd("auth found");
            }
            dd(Session::get("admin_user"));
        }else {
            # code...
        }
    }

    public function logout()
    {
        if ($this->adminUser->check_login()) {
            $this->adminUser->admin_logout();
            return redirect()->route("admin")->with("success", "logout!, many thanks.");
        }else{
            return redirect()->route("admin")->with("error", "can not logout, please login first!!");
        }
    }

    protected function admin_login($adminUser){
        if (!Session::isStarted()) {
            Session::start();
        }
        Session::push("admin_user", $adminUser);
        Session::save();
    }

    protected function get_user_admin() {
        if (!Session::isStarted()) {
            Session::start();
        }
        return Session::get("admin_user", null);
    }

    public function home()
    {
        return view("adminhtml/templates/home");
    }

    public function default()
    {
        return view("adminhtml/templates/default");
    }
}
