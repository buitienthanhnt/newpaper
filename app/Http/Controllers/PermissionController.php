<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function create() {
        $rules = Rule::all();
        return view("adminhtml/templates/permission/create", ["rules" => $rules]);
    }

    function list() {
        return view("adminhtml/templates/permission/list");
    }
}
