<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    //
    protected $request;
    protected $adminUser;

    function __construct(Request $request, AdminUser $adminUser)
    {
        $this->request = $request;
        $this->adminUser = $adminUser;
    }

    function createUser(Request $request) {
        return view("adminhtml/templates/adminUser/create");
    }

    function insertUser(Request $request) {
        $adminUser = $this->adminUser;
        $adminUser->fill([
            "name" => $request->get("admin_user"),
            "email" => $request->get("admin_email"),
            "password" => Hash::make($request->get("admin_password")),
            "active" => 0
        ]);
        $res = $adminUser->save();
        if ($res) {
            return redirect()->back()->with("success", "add new adminUser");
        }else {
            return redirect()->back("error", "can`t not save adminUser, please check your input data");
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    function listUser(Request $request) {
        $allAdminUser = AdminUser::paginate(8);
        return view("adminhtml.templates.adminUser.list", ["allUser" => $allAdminUser]);
    }

    /**
     * https://phpgrid.com/blog/time-to-use-php-return-types-in-your-code/
     * https://www.php.net/manual/en/language.types.declarations.php
     * 
     * @param integer $user_id
     * @param Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function editUser($user_id, Request $request) {
        $user = AdminUser::findOrFail($user_id);
        $allPermission = Permission::all();
        $userPermissions = $user->getPermissionsIds();
        return view("adminhtml.templates.adminUser.edit", ["user" => $user, "permissions" => $allPermission, "userPermissions" => $userPermissions]);
    }

    /**
     * https://phpgrid.com/blog/time-to-use-php-return-types-in-your-code/
     * https://www.php.net/manual/en/language.types.declarations.php
     * 
     * @param integer $user_id
     * @param Request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function updateUser($user_id, Request $request) {
        // dd($request->toArray());

        $user = AdminUser::findOrFail($user_id);
        $user->name = $request->get("admin_user");
        $user->save();
        if ($permisssions = $request->get("permission_values")) {
            $this->adminUser->savePermissions($user, $permisssions);
        }
        return redirect()->back()->with([
            "success" => "updated the user data"
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function deleteUser(Request $request) {
        $user_id = $request->get("user_id");
        $adminUser = AdminUser::find($user_id);
        if ($adminUser) {
            $adminUser->delete();
            return response(json_encode([
                "message" => "deleted the user",
                "code" => 200
            ]));
        }
        return response(json_encode([
            "message" => " can`t delete the user",
            "code" => 301
        ]));
    }
}
