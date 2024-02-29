<?php

namespace App\ViewBlock;

use App\Models\Category;
use App\Models\Paper;
use App\Models\Writer;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class PaperCreateForm implements Htmlable
{
    protected $request;
    protected $category;

    function __construct(
        Request $request,
        Category $category
    ) {
        $this->request = $request;
        $this->category = $category;
    }
    function toHtml(): string
    {
        $contenType = $this->request->get('type', "content");
        $data = null;
        switch ($contenType) {
            case 'carousel':
                $data = $this->carouselForm();
                break;
            default:
                $data = $this->contenForm();
        }
        return view($data['template'], $data['params'])->render();
    }

    function contenForm()
    {
        return [
            'template' => "adminhtml.templates.papers.forms.contenForm",
            "params" => [
                "category_option" => $this->category->category_tree_option(),
                "filemanager_url" => url("adminhtml/file/manager") . "?editor=tinymce5",
                "filemanager_url_base" => url("adminhtml/file/manager"),
                "writers" => Writer::all()
            ]
        ];
    }

    function carouselForm()
    {
        return [
            'template' => "adminhtml.templates.papers.forms.carouselForm",
            "params" => []
        ];
    }
}
