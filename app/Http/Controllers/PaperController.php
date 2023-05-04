<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;

class PaperController extends Controller
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

    public function createPaper()
    {
        return view("adminhtml.templates.papers.create", ["filemanager_url" => url("adminhtml/file/manager")."?editor=tinymce5"]);
    }

    public function insertPaper(){
        $request = $this->request;
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
            "tag" => $request->__get("paper_tag") ? implode("|", $request->__get("paper_tag")) : null,
            "show_writer" => $request->__get("show_writer") ? true : false,
            "show_time" => $request->__get("show_time"),
            "image_path" => ""
        ]);
        $paper->save();
        return redirect()->back()->with("success", "add success");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
            

        }
        return redirect()->back()->with("error", "add error");
    }
}
