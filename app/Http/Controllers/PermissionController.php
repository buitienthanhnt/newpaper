<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Rule;
use App\Models\RulePermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $rule;
    protected $permission;
    protected $rulePermission;

    function __construct(
        Rule $rule,
        Permission $permission,
        RulePermission $rulePermission
    )
    {
        $this->rule = $rule;
        $this->permission = $permission;
        $this->rulePermission = $rulePermission;
    }

    function create()
    {
        $rules = $this->rule->rule_tree_array();
        $ul_rules = $this->rule->root_rules_html();
        return view("adminhtml/templates/permission/create", ["rules" => json_encode(["Name" => "all", "Number" => 0, "Children" => $rules]), "ul_rules" => $ul_rules]);
    }

    function list()
    {
        $permission = $this->permission->paginate(8);
        return view("adminhtml/templates/permission/list", ["permissions" => $permission]);
    }

    function insert(Request $request)
    {
        $params = $request->toArray();
        $rules = array_filter($params, function ($value, $key) {
            var_dump($key, $value);
            if ($value == "on") {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $rules = array_map(fn($value): String => str_replace("checkbox-", "", $value), array_keys($rules));
        $new_permission = new Permission(["label" => $params["label"]]);
        $value = $new_permission->save();
        if ($value) {
            $check_insert_rules = $this->rulePermission->insert_permission_rules($new_permission->id, $rules);
            return redirect()->back()->with("success", "add new permission!!");
        }
        return redirect()->back()->with("error", "cant insert permission");
    }

    function edit() {
        return;
    }

    function update() {
        return;
    }

    function delete(Request $request) {
        $permission_id = $request->get("permission_id");
        if ($permission_id) {
            try {
                $permission = $this->permission->find($permission_id);
                $rulePermission = $permission->rulePermission()->getResults();
                foreach ($rulePermission as $rule) {
                    $rule->delete();
                }
                $permission->delete();
                return response(json_encode([
                    "code" => "200",
                    "value" => "deleted: $permission->label success"
                ]), 200);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return response(json_encode([
            "code" => "401",
            "value" => "can not delete, please try again."
        ]), 401);
    }

}
