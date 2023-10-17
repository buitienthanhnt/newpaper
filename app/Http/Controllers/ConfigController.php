<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Nan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{
    use Nan;

    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction
    ) {
        $this->helperFunction = $helperFunction;
    }

    function list()
    {
        $allOfCoreConfig = null;
        try {
            DB::beginTransaction();
            $allOfCoreConfig = DB::table($this->coreConfigTable())->select()->get();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
        return view("adminhtml.templates.config.list", compact("allOfCoreConfig"));
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
