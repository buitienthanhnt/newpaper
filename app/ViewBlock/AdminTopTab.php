<?php

namespace App\ViewBlock;

use App\Models\User;
use App\Models\ViewSource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class AdminTopTab implements Htmlable
{
    protected $template = 'adminhtml.templates.share.topTab';

    function __construct()
    {
    }

    function paperList(): \Illuminate\Database\Eloquent\Builder
    {
        $list_paper = ViewSource::where('type', '=', 'paper');
        return $list_paper;
    }

    function paperViewCount() : int {
        return $this->paperList()->sum('value');
    }

    function userList() : int {
        return User::all()->count();
    }

    function paperInFirebase() : int {
        $papersInFirebase = [];
        if (Cache::has('paper_in_firebase')) {
            $papersInFirebase = Cache::get('paper_in_firebase');
        }
        return count($papersInFirebase);
    }

    function toHtml(): string
    {
        $list_data = [
            "page_count" => $this->paperList()->count(),
            "paper_view_count" => $this->paperViewCount(),
            "user_count" => $this->userList(),
            "paper_in_firebase" => $this->paperInFirebase()
        ];
        return view($this->template, ['list_data' => $list_data])->render();
    }
}
