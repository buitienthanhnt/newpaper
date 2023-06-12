<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function __construct()
    {

    }

    public function adminLogin()
    {
        return view("adminhtml/templates/adminLogin");
    }

    public function loginPost()
    {
        # code...
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
