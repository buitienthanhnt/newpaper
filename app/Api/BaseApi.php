<?php

namespace App\Api;

use App\Helper\ImageUpload;
use App\Services\FirebaseService;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Thanhnt\Nan\Helper\LogTha;
use Kreait\Firebase\Factory;

class BaseApi
{
    use ImageUpload;

    const STORERAGE_BUGKET = 'newpaper';

    protected $firebase;
    protected $firebaseDatabase;
    protected $fireStore;
    protected $remoteConfig;
    protected $logTha;

    public function __construct(
        FirebaseService $firebaseService,
        LogTha $logTha
    ) {
        $this->logTha = $logTha;
        $this->firebase = $firebaseService->firebase;
        $this->firebaseDatabase = $this->firebase->createDatabase();
        $this->fireStore = $firebaseService->fireStore;
        $this->remoteConfig = $firebaseService->remoteConfig;
    }

    public function upLoadImageFirebase(string $image_link, $folder = null)
    {
        $firebaseFolder = $folder ? $folder . '/' : self::STORERAGE_BUGKET . '/';
        $real_path = $this->url_to_real($image_link);
        if (empty($real_path)) {
            return null;
        }
        $image = fopen($real_path, 'r');
        try {
            $fileType = explode('.', $image_link);
            $fileType = $fileType[count($fileType) - 1];
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $bucket = $storage->getBucket();

            // upload 1 file lên store
            $response = $bucket->upload($image, ['name' => $firebaseFolder . Str::random(10) . '.' . $fileType]);
            $uri = $response->info()['mediaLink'];
            return str_replace(Rest::DEFAULT_API_ENDPOINT . '/download/storage/v1', 'https://firebasestorage.googleapis.com/v0', $uri);
        } catch (\Throwable $th) {
            // echo ($th->getMessage());
        }
        return null;
    }

    /**
     * remove image in storage of firebase.
     * @param string $url_path
     * @return bool
     */
    public function removeImageFirebase(string $url_path)
    {
        try {
            /**
             * @var \Kreait\Firebase\Contract\Storage $storage
             */
            $storage = $this->firebase->createStorage();
            $fileName = self::STORERAGE_BUGKET . "/" . explode("/" . self::STORERAGE_BUGKET . "/", parse_url(urldecode($url_path))['path'], 2)[1];
            $storage->getBucket()->object($fileName)->delete();
            return true;
        } catch (\Throwable $th) {
            $this->logTha->logFirebase('warning', 'can not remove image paper by: ' . $th->getMessage(), ['line' => $th->getLine()]);
        }
        return false;
    }

    /**
     * get default image of firebase remote config(sync with firebase)
     * @return string
     */
    public static function getDefaultImagePath(): string
    {
        if (Cache::has('default_image')) {
            return Cache::get('default_image');
        }
        try {
            $default_image = '';
            try {
                $config_path = storage_path("app/" . FirebaseService::CONNECT_FIREBASE_PROJECT . "/firebaseConfig.json");
                $default_image = (new Factory)->withServiceAccount($config_path)->createRemoteConfig()->get()->parameters()['default_image']->toArray()['defaultValue']['value'];
            } catch (\Throwable $th) {
                $default_image = DB::table('core_config')->where('name', 'default_image')->get()->first()->value;
            }
            Cache::put("default_image", $default_image);
            return $default_image;
        } catch (\Throwable $th) {
        }
        return '';
    }
}
