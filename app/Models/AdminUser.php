<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function admin_login($user_name, $pass_word) : mixed {
        if ($user_name && $pass_word) {
            $get_by_name = $this->where("name")->get();
            if ($get_by_name->count()) {
                $admin_user = $get_by_name->first();
                return Hash::check($pass_word, $admin_user->password);
            }
        }
        return null;
    }

    function admin_logout() : void{

    }

    public function get_admin_user() : mixed {
        $this->session_begin();
        return Session::get("user_admin", null);
    }

    public function check_login() : bool {
        $this->session_begin();
        return Session::has("admin_user") ;
    }

    public function session_begin() : void {
        if (!Session::isStarted()) {
            Session::start();
        }
    }
}
