<?php

namespace App\Services;

use Kreait\Firebase;
use Kreait\Firebase\Factory;

// tài liệu php firebase.
// https://firebase-php.readthedocs.io/en/stable/

// https://github.com/kreait/laravel-firebase#usage

class FirebaseService
{
    /**
     * @var Firebase
     */
    public $firebase;

    public function __construct()
    {
        $path = storage_path('app/firebaseConfig.json');
        $this->firebase = (new Factory)->withServiceAccount($path)->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    }
}
