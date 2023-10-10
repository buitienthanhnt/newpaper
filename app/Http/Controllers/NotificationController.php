<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function registerFcm(Request $request) {
        dd($request->getContent());
    }
}
