<?php

namespace App\Http\Controllers;

use App\Helper\ImageUpload;
use Illuminate\Http\Request;

class WriterController extends Controller
{
    use ImageUpload;
    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function listOfWriter()
    {
      if (view()->exists('adminhtml.writer.list'))
      {
          return view("adminhtml.writer.list");
      }else {
        return redirect("adminhtml")->with("not_page", 1);
      }
    }

    public function createWriter()
    {
        if (view()->exists('adminhtml.templates.writer.create'))
        {
            return view("adminhtml.templates.writer.create");
        }else {
            redirect("admin")->with("no_router", 1);
        }
    }

    public function insertWriter()
    {
        $request = $this->request;
        $this->uploadImage($request);

        dd($request->toArray());
        return;
    }
}
