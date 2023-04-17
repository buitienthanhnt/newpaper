<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WriterController extends Controller
{
    //

    public function listOfWriter()
    {
      if (view()->exists('adminhtml.writer.list'))
      {
          return view("adminhtml.writer.list");
      }else {
        return redirect("adminhtml")->with("not_page", 1);
      }
    }
}
