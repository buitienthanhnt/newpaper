<?php

namespace App\Models;

use App\Helper\Nan;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Nan;
    protected $guarded = [];
    protected $request;

    public function __construct()
    {
        $this->session_begin();
    }

    public function admin_login($user_name, $pass_word): mixed
    {
        if ($user_name && $pass_word) {
            $get_by_name = $this->where("name")->get();
            if ($get_by_name->count()) {
                $admin_user = $get_by_name->first();
                return Hash::check($pass_word, $admin_user->password);
            }
        }
        return null;
    }

    function admin_logout(): void
    {
        if ($admin_user = Session::get("admin_user")) {
            Session::remove("admin_user");
        }
    }

    public function get_admin_user(): mixed
    {
        // $this->session_begin();
        return Session::get("admin_user", null);
    }

    public function check_login(): bool
    {
        // $this->session_begin();
        return Session::has("admin_user");
    }

    public function session_begin(): void
    {
        if (!Session::isStarted()) {
            Session::start();
        }
    }

    /**
     * login by admin user
     *
     * @return bool
     */
    public function login_by_admin_user(): bool
    {
        try {
            if ($this->toArray()) {
                Session::push("admin_user", $this);
                Session::save();
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }

    public function savePermissions($user_id, $permissions)
    {
        if ($user_id && $permissions) {
            DB::beginTransaction();
            try {
                foreach ($permissions as $permission) {
                    DB::table($this->userPermissionTable())->updateOrInsert(["permission_id" => $permission, "user_id" => $user_id]);
                }
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
    }

    public function __destruct()
    {
    }
}
