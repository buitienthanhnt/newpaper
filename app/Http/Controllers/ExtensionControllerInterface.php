<?php

namespace App\Http\Controllers;

interface ExtensionControllerInterface{
    const CONTROLLER_NAME = 'ExtensionController';

    const HOME_INFO = 'homeInfo';

    const SEARCH = 'search';
    const PAPER_MOST_VIEW = 'getPaperMostView';
    const LOGIN = 'login';
    const USER_INFO = 'getUserInfo';

    public function search();

    public function getPaperMostView();

    public function login();

    public function getUserInfo();
}
