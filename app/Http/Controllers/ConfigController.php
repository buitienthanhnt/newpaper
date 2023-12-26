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

    function update(Request $request)
    {
        $params = $request->all();
        unset($params['_token']);
        try {
            if (count($params)) {
                foreach ($params as $key => $value) {
                    $this->helperFunction->updateConfig($key, $value);
                }
            }
            return redirect()->back()->with("success", "saved config value!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "save config error!");
        }
    }

    function delete(Request $request) {
        $config_id = $request->get('config_id');
        try {
            $this->helperFunction->deleteConfig($config_id);
            return response(json_encode([
                "code" => "200",
                "value" => "deleted: success!"
            ]), 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => "401",
            "value" => "deleted: success!"
        ]), 401);
    }
}
