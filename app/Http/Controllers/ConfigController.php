<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Helper\Nan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\LogTha;

class ConfigController extends Controller
{
    use Nan;

    protected $helperFunction;
    protected $logTha;

    function __construct(
        HelperFunction $helperFunction,
        LogTha $logTha
    )
    {
        $this->helperFunction = $helperFunction;
        $this->logTha = $logTha;
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
        extract($this->helperFunction->saveConfig($request->get("name"), $request->get("value")));
        if ($status) {
            $this->logTha->logEvent('info', "added config: ", [
                'key' => $configValue->name,
                'value' => $configValue->value
            ]);
        }
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
                    $this->logTha->logEvent('info', "updated config: ", [
                        'key' => $key,
                        'old value' => 'now default null',
                        'new value' => $value
                    ]);
                }
            }
            return redirect()->back()->with("success", "saved config value!");
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", "save config error!");
        }
    }

    function delete(Request $request)
    {
        $config_id = $request->get('config_id');
        try {
            extract($this->helperFunction->deleteConfig($config_id));
            if (!$configValue) {
                return response(json_encode([
                    "code" => "401",
                    "value" => "deleted: success!"
                ]), 401);
            }
            $this->logTha->logEvent('warning', "deleted config with : {key} & {value}", [
                'key' => $configValue->name,
                'value' => $configValue->value
            ]);
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
