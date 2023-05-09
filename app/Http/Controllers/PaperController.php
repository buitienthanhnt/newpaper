<?php

namespace App\Http\Controllers;

use App\Helper\Page;
use App\Models\Category;
use App\Models\Paper;
use App\Models\Writer;
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

    public function listPaper()
    {
        $page_lists = $this->paper->paginate(8);
        foreach ($page_lists as &$page) {
            $resuls = [];
            $categories = $page->to_category()->get("category_id")->toArray();
            if ($categories) {
                $id_of_categories = array_map(function ($itm) {
                    return $itm["category_id"];
                }, $categories);

                $resuls = Category::all(['id', "name"])->whereIn("id", $id_of_categories);
            }
            $page->categories = $resuls;
        }
        return view("adminhtml.templates.papers.list", compact("page_lists"));
    }

    public function createPaper()
    {
        $writers = Writer::all();
        return view("adminhtml.templates.papers.create", [
            "category_option" => $this->category->category_tree_option(),
            "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
            "filemanager_url_base" => url("adminhtml/file/manager"),
            "writers" => $writers
        ]);
    }

    public function insertPaper()
    {
        $request = $this->request;
        $category_option = $request->__get("category_option");
        try {
            $paper = $this->paper;
            $paper->fill([
                "title" => $request->__get("page_title"),
                "url_alias" => $request->__get("alias") ?: str_replace(" ", "_", $request->get("page_title")),
                "short_conten" => $request->__get("short_conten"),
                "conten" => $request->__get("conten"),
                "active" => $request->__get("active") ? true : false,
                "show" => $request->__get("show") ? true : false,
                "auto_hide" => $request->__get("auto_hide") ? true : false,
                "show_writer" => $request->__get("show_writer") ? true : false,
                "show_time" => $request->__get("show_time"),
                "image_path" => $request->__get("image_path") ?: "",
                "writer" => $request->get("writer", null)
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

    public function editPaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $writers = Writer::all();
        $filemanager_url = url("adminhtml/file/manager") . "?editor=tinymce5";
        $filemanager_url_base = url("adminhtml/file/manager");
        $paper_category = array_column($paper->to_category()->get(["category_id"])->toArray(), "category_id");
        $category_option = $this->category->setSelected($paper_category)->category_tree_option();

        return view("adminhtml.templates.papers.edit", compact("paper", "writers", "category_option", "filemanager_url", "filemanager_url_base"));
    }

    public function updatePaper($paper_id)
    {
        $paper = $this->paper->find($paper_id);
        $request = $this->request;
        if ($paper) {
            try {
                $paper->fill([
                    "title" => $request->__get("page_title"),
                    "url_alias" => $request->__get("alias") ?: str_replace(" ", "_", $request->get("page_title")),
                    "short_conten" => $request->__get("short_conten"),
                    "conten" => $request->__get("conten"),
                    "active" => $request->__get("active") ? true : false,
                    "show" => $request->__get("show") ? true : false,
                    "auto_hide" => $request->__get("auto_hide") ? true : false,
                    "show_writer" => $request->__get("show_writer") ? true : false,
                    "show_time" => $request->__get("show_time"),
                    "image_path" => $request->__get("image_path") ?: "",
                    "writer" => $request->get("writer", null)
                ]);
                $paper->save();
                if ($new_id = $paper->id) {
                    $this->delete_page_category($paper);
                    $this->insert_page_category($new_id, $request->get("category_option"));
                    $this->delete_page_tag($paper);
                    $this->insert_page_tag($request->__get("paper_tag"), $new_id, Paper::PAGE_TAG);
                }
                return redirect()->back()->with("success", "updated success");
            } catch (\Throwable $th) {
                $th->getMessage();
            }
        }
        return redirect()->back()->with("error", "update error, please try again!");
    }

    public function deletePaper()
    {
        $request = $this->request;
        try {
            if ($paper_id = $request->get("paper_id")) {
                $paper = $this->paper::find($paper_id);
                if ($paper && $paper->id) {
                    $tags = $paper->to_tag()->getResults();
                    $categories = $paper->to_category()->getResults();
                    if ($tags->count()) {
                        foreach ($tags as $tag) {
                            $tag->forceDelete();
                        }
                    }

                    if ($categories->count()) {
                        foreach ($categories as $category) {
                            $category->forceDelete();
                        }
                    }
                    $paper->delete();
                    return response(json_encode([
                        "code" => "200",
                        "value" => "deleted: success!"
                    ]), 200);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            "code" => 401,
            "value" => "delete error. Please try again!"
        ]), 401);
    }
}
