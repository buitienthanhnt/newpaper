<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ConfigCategory;
use App\Models\Paper;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    protected $request;
    protected $paper;

    public function __construct(
        Request $request,
        Paper $paper
    )
    {
        $this->request = $request;
        $this->paper = $paper;
    }

    public function homePage()
    {
        $trending_left = $this->paper->take(3)->get();
        $trending_right = $this->paper->orderBy("updated_at", "DESC")->take(2)->get();
        $center_category = ConfigCategory::where("path", "center_category")->firstOr(function(){
            return null;
        });
        if ($center_category) {
            $list_center = Category::find(explode("&", $center_category->value));
        }

        return view("frontend/templates/homeconten", compact("trending_left", "trending_right", "list_center"));
    }
}
