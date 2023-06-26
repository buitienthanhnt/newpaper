<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    protected $rule;
    function __construct(
        Rule $rule
    ) {
        $this->rule = $rule;
    }

    function list()
    {
        $all_rules = Rule::paginate(8);
        return view("adminhtml/templates/rule/list", ["rules" => $all_rules]);
    }

    function create()
    {
        $rules = Rule::all();
        $rules_option = $this->rule->rule_tree_option();
        return view("adminhtml/templates/rule/create", ["rules" => $rules, "rules_option" => $rules_option]);
    }

    function insert(Request $request)
    {
        try {
            $rule = new Rule($request->toArray() + ["parent_id" => $request->get("parent_id", 0)]);
            $save_status = $rule->save();
        } catch (\Throwable $th) {
            return redirect()->back()->with("error", $th->getMessage());
        }

        if ($save_status) {
            return redirect()->back()->with("success", "created new rule!");
        }
        return redirect()->back()->with("error", "can not create rule right now!");
    }

    function edit(Request $request)
    {
        $parent = null;
        $rule = Rule::find($request->get("id"));
        if ($parent_id = $request->get("parent_id")) {
            $parent = Rule::find($parent_id);
        }

        $children = $rule->getChildren();
        return view("adminhtml/templates/rule/edit", ["rule" => $rule, "parent" => $parent, "children" => $children]);
    }

    function delete(Request $request)
    {
        try {
            $rule_id = $request->get("rule_id");
            if ($rule = Rule::find($rule_id)) {
                $rule->delete();
                return response(json_encode([
                    "code" => "200",
                    "value" => "deleted: success!"
                ]), 200);
            }
            return response(json_encode([
                "code" => 401,
                "value" => "delete error. Please try again!"
            ]), 401);
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => 401,
            "value" => "delete error. Please try again!"
        ]), 401);
    }

    function addChildren($parent_id = null, Request $request)
    {
        if ($request->method() == "GET") {
            if ($parent_id) {
                $parent = Rule::find($parent_id);
                return view("adminhtml/templates/rule/addChildren", ["parent" => $parent]);
            }
        }else if ($request->method() == "POST") {
            $save_status = $this->save_rule($request);
            if ($save_status) {
                return redirect()->back()->with("success", "add children rule: ");
            }else {
                return redirect()->back()->with("error", "error for add new children!");
            }
        }

        return redirect()->back()->with("error", "can not add new children now!");
    }

    /**
     * save new rule
     * @return bool
     */
    protected function save_rule(Request $request) {
        try {
            $rule = new Rule($request->toArray() + ["parent_id" => $request->get("parent_id", 0)]);
            $save_status = $rule->save();
        } catch (\Throwable $th) {
            $save_status = false;
        }
        return $save_status;
    }
}
