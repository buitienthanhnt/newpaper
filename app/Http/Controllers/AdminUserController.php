<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    //
    protected $request;
    protected $adminUser;

    function __construct(Request $request, AdminUser $adminUser)
    {
        $this->request = $request;
        $this->adminUser = $adminUser;
    }

    function createUser(Request $request) {
        return view("adminhtml/templates/adminUser/create");
    }

    function insertUser(Request $request) {
        // dd($request->toArray());

        $adminUser = $this->adminUser;
        $adminUser->fill([
            "name" => $request->get("admin_user"),
            "email" => $request->get("admin_email"),
            "password" => Hash::make($request->get("admin_password")),
            "active" => 0
        ]);
        $res = $adminUser->save();
        if ($res) {
            dd("created new adminuser");
        }else {
            dd("cant create admin user");
        }
    }
}
