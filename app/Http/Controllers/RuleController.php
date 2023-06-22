<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{

    function list() {
        $all_rules = Rule::paginate(8);
        return view("adminhtml/templates/Rule/list", ["rules" => $all_rules]);
    }

    function create()  {
        return view("adminhtml/templates/Rule/create");
    }

    function insert(Request $request) {
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

    function edit(Request $request) {
        $parent = null;
        if ($parent_id = $request->get("parent_id")) {
            $parent = Rule::find($parent_id);
        }
        return view("adminhtml/templates/Rule/create", ["parent" => $parent]);
    }
}
