<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Rule;
use App\Models\PermissionRules;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class PermissionController extends Controller
{
    protected $router;
    protected $rule;
    protected $permission;
    protected $permissionRules;

    function __construct(
        Router $router,
        Rule $rule,
        Permission $permission,
        PermissionRules $permissionRules
    )
    {
        $this->router = $router;
        $this->rule = $rule;
        $this->permission = $permission;
        $this->permissionRules = $permissionRules;
    }

    function create()
    {
        $rules = $this->rule->rule_tree_array();
        $ul_rules = $this->rule->root_rules_html();
        $prefixGroup = $this->allRules();
        return view("adminhtml/templates/permission/create", ["rules" => json_encode($prefixGroup), "ul_rules" => $ul_rules]);
        // return view("adminhtml/templates/permission/create", ["rules" => json_encode(["Name" => "all", "Number" => 0, "Children" => $rules]), "ul_rules" => $ul_rules]);
    }

    function allRules() : array {

        $allOfRoute = $this->router->getRoutes();
        $actions = collect($allOfRoute)->map(function($item){
            $action =  $item->getAction();
            if (strpos($action["prefix"], "adminhtml") !== false) {
                return $action;
            }
        })->filter();
        return $prefixGroup = $this->actionByController($actions->toArray());
    }

    function actionByController($actions = []) : array {
        if ($actions) {
            $controllerGroups = [];
            $prefixGroups = [];
            foreach ($actions as $action) {
                $prefixGroups[$action["prefix"]][] = [
                    "Name"     => explode("@", $action["controller"], 2)[1],
                    "Number"   => str_replace(["\\", "/", "@"], ["-", "__", "_"], $action["controller"]),
                    "Children" => []
                ];
            }

            $rulesTree = [];
            foreach ($prefixGroups as $key => $value) {
                $rulesTree[] = [
                    "Name"      => str_replace("/", " ", $key),
                    "Number"    => $key,
                    "Children"  => $value
                ];
            }
            return [
                "Name" => "root admin",
                "Number" => "rootAdmin",
                "Children" => $rulesTree
            ];
        }
        return [];
    }

    function list()
    {
        $permission = $this->permission->paginate(8);
        return view("adminhtml/templates/permission/list", ["permissions" => $permission]);
    }

    function filterRules($rules): array{
        if (in_array("rootAdmin", $rules)) {
            return ["rootAdmin"];
        }
        return $rules;
    }

    function insert(Request $request)
    {
        $params = $request->toArray();
        $rules = array_filter($params, function ($value, $key) {
            if ($value == "on") {
                return true;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $rules = $this->filterRules(array_map(fn($value): String => str_replace("checkbox-", "", $value), array_keys($rules)));
        $new_permission = new Permission(["label" => $params["label"]]);
        $value = $new_permission->save();
        if ($value) {
            $check_insert_rules = $this->permissionRules->insert_permission_rules($new_permission->id, $rules);
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
                $permissionRules = $permission->rulePermission()->getResults();
                foreach ($permissionRules as $rule) {
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

    /**
     * @/return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function detail($permission_id): ?\Illuminate\Contracts\View\View{
        $permission = Permission::find($permission_id);
        $prefixGroup = $this->allRules();
        $permissionRules = $permission->allRules();
        return view("adminhtml.templates.permission.detail", ["permission" => $permission, "rules" => json_encode($prefixGroup), "rules_selected" => json_encode($permissionRules)]);
    }

}
