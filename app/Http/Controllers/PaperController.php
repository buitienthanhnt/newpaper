<?php

namespace App\Http\Controllers;

use App\Helper\Page;
use App\Models\Category;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperController extends Controller
{
    use Page;

    protected $request;
    protected $paper;
    protected $category;

    public function __construct(
        Request $request,
        Paper $paper,
        Category $category
    ) {
        $this->request = $request;
        $this->paper = $paper;
        $this->category = $category;
    }

    public function createPaper()
    {
        return view("adminhtml.templates.papers.create", [
            "category_option" => $this->category->category_tree_option(),
            "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
            "filemanager_url_base" => url("adminhtml/file/manager")
        ]);
    }

    public function insertPaper()
    {
        $request = $this->request;
        $category_option = $request->__get("category_option");
        // dd($request->toArray());

        try {
            $paper = $this->paper;
            $paper->fill([
                "title" => $request->__get("page_title"),
                "url_alias" => $request->__get("alias"),
                "short_conten" => $request->__get("short_conten"),
                "conten" => $request->__get("conten"),
                "active" => $request->__get("active") ? true : false,
                "show" => $request->__get("show") ? true : false,
                "auto_hide" => $request->__get("auto_hide") ? true : false,
                // "tag" => $request->__get("paper_tag") ? implode("|", $request->__get("paper_tag")) : null,
                "show_writer" => $request->__get("show_writer") ? true : false,
                "show_time" => $request->__get("show_time"),
                "image_path" => ""
            ]);
            $paper->save();
            if ($new_id = $paper->id) {
                $this->insert_page_category($new_id, $category_option);
                $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAGE_TAG);
            }
            return redirect()->back()->with("success", "add success");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
        return redirect()->back()->with("error", "add error");
    }
}
