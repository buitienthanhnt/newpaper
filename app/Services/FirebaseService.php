<?php

namespace App\Services;

use Exception;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;
// https://firebase-php.readthedocs.io/en/stable/
class FirebaseService
{
    /**
     * @var Firebase
     */
    public $firebase;

    public function __construct()
    {
        $path = storage_path('app/laravel1-407017-8e7dd2878a78.json');
        $this->firebase = (new Factory)->withServiceAccount($path)->withDatabaseUri('https://laravel1-407017-default-rtdb.firebaseio.com/');
    }
}
