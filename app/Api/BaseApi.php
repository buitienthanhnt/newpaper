<?php

namespace App\Api;

use App\Helper\ImageUpload;
use App\Services\FirebaseService;
use Carbon\Carbon;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\MessagingException;
use Thanhnt\Nan\Helper\LogTha;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class BaseApi
{
    use ImageUpload;

    const STORERAGE_BUGKET = 'newpaper';

    protected $firebase;
    protected $firebaseDatabase;
    protected $fireStore;
    protected $cloudMessage;
    protected $logTha;

    function __construct(
        FirebaseService $firebaseService,
        LogTha $logTha
    ) {
        $this->logTha = $logTha;
        $this->firebase = $firebaseService->firebase;
        $this->firebaseDatabase = $this->firebase->createDatabase();
        $this->fireStore = $firebaseService->fireStore;
        $this->cloudMessage = $firebaseService->cloudMessage;
    }

    function upLoadImageFirebase(string $image_link)
    {
        $firebaseFolder = self::STORERAGE_BUGKET . '/';
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
    function removeImageFirebase(string $url_path)
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

    function pushMessage() // https://magecomp.com/blog/push-notifications-in-laravel-with-firebase/
    {
        // D:\xampp\htdocs\laravel1\vendor\kreait\firebase-php\src\Firebase\Messaging\CloudMessage.php

        try {
            $message = CloudMessage::fromArray([ 
                // 'topic' => 'all',
                'notification' => [
                    'body' => 'Xin chào đây là thông báo đầu tiên',
                    'title' => 'Thông báo đầu tiên'
                ],
                'time' => Carbon::now()->timestamp
            ]);
    
            $res = $this->cloudMessage->send($message);
        }catch (MessagingException $e) {
            dd($e->getMessage());
        }

        return response()->json(['message' => 'Push notification sent', 'data' => $res]);
    }
}
