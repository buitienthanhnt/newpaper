<?php

namespace App\Http\Controllers;

interface UserControllerInterface{
    const CONTROLLER_NAME = 'UserController';
    const PREFIX = 'user';

    const FRONT_REGISTER_ACCOUNT = 'registerAccount';
    const FRONT_ADD_ACCOUNT = 'addAccount';
    const FRONT_LOGIN_PAGE = 'loginPage';
    const FRONT_LOGIN_POST = 'loginPost';
    const FRONT_USER_DETAIL = 'userDetail';
    const FRONT_USER_LOGOUT = 'logout';

    public function registerAccount();

    public function addAccount();

    public function loginPage();

    public function loginPost();

    public function userDetail();

    public function logout();

}
