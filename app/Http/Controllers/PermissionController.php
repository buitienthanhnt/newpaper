<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $rule;
    function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    function create() {
        $rules = $this->rule->rule_tree_array();
        $ul_rules = $this->rule->root_rules_html();
        return view("adminhtml/templates/permission/create", ["rules" => json_encode(["Name" => "all", "Number" => 0, "Children" => $rules]), "ul_rules" => $ul_rules]);
    }

    function list() {
        return view("adminhtml/templates/permission/list");
    }
}
