<?php

namespace App\Helper;

use App\Models\Paper;

class HelperFunction
{
    use DomHtml;

    // post request with request params. 
    public function push_notification(array $notification_fcm, Paper $paper): bool
    {
        $curl = curl_init();
        $url = "https://fcm.googleapis.com/fcm/send";

        $authHeaders = array();
        $authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        $authHeaders[] = 'Authorization: ' . "key=AAAAeBGZHtQ:APA91bGggB93_pNvy07wXXNSDhqCeq4cx0DUFrZ569ngqgXxanHejv8adyIvuE-GSn0Aui8tSfnadFU1Wc3BykaiOwnVT_h_pzIvU6JlfKTF2rTQ3st28vO9TXAFUxsSWG7BDkDTHUbl";

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'laravel1',
            CURLOPT_POST => 1,
            CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL
            CURLOPT_POSTFIELDS => http_build_query(array(
                'registration_ids' => array_map(fn ($notification) => $notification->fcmToken, $notification_fcm),
                'notification' => [
                    "title" => $paper->title,
                    "body" => $paper->short_conten
                ],
                "data" => [
                    "link" => $paper->url_alias,
                    "id" => $paper->id,
                    "screen" => "PaperDetail/$paper->id",
                    "data" => [
                        "id" => $paper->id
                    ]
                ]
            ))
        ));
        $resp = curl_exec($curl);
        var_dump($resp);
        curl_close($curl);

        return $resp;
    }

    // post request with json params in body
    public function push_notification_json(array $notification_fcm, Paper $paper): bool
    {
        $data = array(
            "serverKey" => "1:515691323092:android:9cc570b98e4b444d95e541",
            'registration_ids' => array_map(fn ($notification) => $notification["fcmToken"], $notification_fcm),
            'notification' => [
                "title" => $paper->title,
                "body" => $paper->short_conten
            ],
            "data" => [
                "link" => $paper->url_alias,
                "id" => $paper->id,
                "screen" => "PaperDetail/$paper->id",
                "data" => [
                    "id" => $paper->id
                ]
            ]
        );
        $data_string = json_encode($data);
        $url = "https://fcm.googleapis.com/fcm/send";
        // $url = "http://laravel1.com/api/testPost";

        $authHeaders = array();
        $authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        $authHeaders[] = 'Authorization: ' . "key=AAAAeBGZHtQ:APA91bGggB93_pNvy07wXXNSDhqCeq4cx0DUFrZ569ngqgXxanHejv8adyIvuE-GSn0Aui8tSfnadFU1Wc3BykaiOwnVT_h_pzIvU6JlfKTF2rTQ3st28vO9TXAFUxsSWG7BDkDTHUbl";


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$authHeaders);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: ' . "key=AAAAeBGZHtQ:APA91bGggB93_pNvy07wXXNSDhqCeq4cx0DUFrZ569ngqgXxanHejv8adyIvuE-GSn0Aui8tSfnadFU1Wc3BykaiOwnVT_h_pzIvU6JlfKTF2rTQ3st28vO9TXAFUxsSWG7BDkDTHUbl"
            )
        );

        $result = curl_exec($curl);
        curl_close($curl);

        echo ($result);
        return $result;
    }

    // get request curl data
    public function curl_get()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_URL => 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22hanoi%2C%20vietnam%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys',
            CURLOPT_USERAGENT => 'Viblo test cURL Request',
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $resp = curl_exec($curl);

        //Dữ liệu thời tiết ở dạng JSON
        $weather = json_decode($resp);
        var_dump($weather);

        curl_close($curl);
    }
}
