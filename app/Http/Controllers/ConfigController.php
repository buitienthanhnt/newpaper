<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Nan;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    use Nan;

    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction
    ) {
        $this->helperFunction = $helperFunction;
    }

    function create()
    {
        return view("adminhtml.templates.config.create");
    }

    function insert(Request $request)
    {
        $this->helperFunction->saveConfig($request->get("name"), $request->get("value"));
        return redirect()->back()->with("success", "saved config value!");
    }
}
