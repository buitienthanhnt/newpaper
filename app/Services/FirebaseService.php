<?php

namespace App\Services;

use Kreait\Firebase;
use Kreait\Firebase\Factory;

// tài liệu php firebase.
// https://firebase-php.readthedocs.io/en/stable/
// https://github.com/kreait/laravel-firebase#usage

// nên chọn: firebase-adminsdk(gốc) trong list service để tạo key json.
// https://console.cloud.google.com/iam-admin/serviceaccounts/details/111481399996955463387/keys?project=react-cli4&supportedpurview=project :cli4
// https://console.cloud.google.com/iam-admin/serviceaccounts/details/109858435023888922380/keys?project=cli6-c4945&supportedpurview=project :cli6

class FirebaseService
{
    /**
     * @var Firebase
     */
    public $firebase;

    public function __construct()
    {
        $path = storage_path('app/firebaseConfig.json');
        $this->firebase = (new Factory)->withServiceAccount($path)->withDatabaseUri(env('FIREBASE_DATABASE_URL', 'https://react-cli4-default-rtdb.firebaseio.com/'));
    }
}
