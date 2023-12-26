<?php

namespace App\Helper;

use App\Models\Paper;
use Exception;
use Illuminate\Support\Facades\DB;
use Thanhnt\Nan\Helper\DomHtml;

class HelperFunction
{
    use DomHtml;
    use Nan;

    public function getConfig(string $name, $default = null)
    {
        try {
            $value = DB::table($this->coreConfigTable())->where('name', $name)->select()->first()->value;
            return $value;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $default;
    }

    // save core config value.
    public function saveConfig(string $name, string $value, string $type = "text", string $description = null): array
    {
        DB::beginTransaction();
        try {
            $insert_value = DB::table($this->coreConfigTable())->updateOrInsert(["name" => $name, "value" => $value, "description" => $description, "type" => $type]);
            DB::commit();
            return ["status" => true, "value" => $insert_value];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return ["status" => false, "value" => null];
    }

    function updateConfig(string $name, string $value, string $type = "text", string $description = null)
    {

        DB::beginTransaction();
        try {
            $insert_value = DB::table($this->coreConfigTable())->where('name', $name)->limit(1)->update(["name" => $name, "value" => $value, "description" => $description, "type" => $type]);
            DB::commit();
            return ["status" => true, "value" => $insert_value];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return ["status" => false, "value" => null];
    }

    function deleteConfig(int $config_id)
    {
        DB::beginTransaction();
        try {
            DB::table($this->coreConfigTable())->delete($config_id);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
        return false;
    }

    // repalce image url for app by use ip address.
    public function replaceImageUrl(string $imageUrl = ""): string
    {
        if (!$imageUrl) {
            return $this->defaultUrl();
        }
        $domain = "";
        $ip = "";
        $main = "";
        $is_windown = false;
        try {
            DB::beginTransaction();
            $domain = DB::table($this->coreConfigTable())->where("name", "=", "domain")->select()->first()->value;
            $main = DB::table($this->coreConfigTable())->where("name", "=", "main")->select()->first()->value;
            $ip = DB::table($this->coreConfigTable())->where("name", "=", "ip")->select()->first()->value;
        } catch (\Throwable $th) {
            //throw $th;
            return $imageUrl;
        }
        // support for windown platform
        try {
            $is_windown = (bool) DB::table($this->coreConfigTable())->where("name", "=", "is_windown")->select()->first()->value;
        } catch (\Throwable $th) {
        }

        $img = $is_windown ? str_replace($domain, $ip, $imageUrl) : str_replace($domain, $ip . "/" . $main . "/public", $imageUrl);
        return $img;
    }

    // allway use default image url.
    public function defaultUrl(): string
    {
        $ip = "";
        $main = "";
        try {
            DB::beginTransaction();
            $main = DB::table($this->coreConfigTable())->where("name", "=", "main")->select()->first()->value;
            $ip = DB::table($this->coreConfigTable())->where("name", "=", "ip")->select()->first()->value;
        } catch (\Throwable $th) {
            return "";
        }
        return "http://" . $ip . "/" . $main . "/public" . "/assets/pub_image/defaul.PNG";
    }

    // post request with request params.
    public function push_notification(array $notification_fcm, Paper $paper): bool
    {
        $curl = curl_init();
        $url = "https://fcm.googleapis.com/fcm/send";

        $authHeaders = array();
        $authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        try {
            $authorization = DB::table($this->coreConfigTable())->where("name", "=", "Authorization")->select()->first()->value;
        } catch (\Throwable $th) {
            $th("not has authorization");
        }
        $authHeaders[] = 'Authorization: ' . $authorization;

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
                    "body" => $paper->short_conten,
                    "image" => $this->replaceImageUrl($paper->image_path),
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
    public function push_notification_json(array $notification_fcm = [], Paper $paper): bool
    {
        if (!$notification_fcm || !$paper) {
            return false;
        }
        $authorization = "";
        try {
            $authorization = DB::table($this->coreConfigTable())->where("name", "=", "Authorization")->select()->first()->value;
        } catch (\Throwable $th) {
            $th("not has authorization");
        }

        $authHeaders[] = 'Content-Type: application/x-www-form-urlencoded';
        $authHeaders[] = 'Authorization: ' . $authorization;
        $data = array(
            'registration_ids' => array_map(fn ($notification) => $notification["fcmToken"], $notification_fcm),
            'notification' => [
                "title" => $paper->title,
                "body" => $paper->short_conten,
                "image" => $this->replaceImageUrl($paper->image_path), // image of notification
                "icon" => "ic_launcher"
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
        $url = "https://fcm.googleapis.com/fcm/send"; // $url = "http://laravel1.com/api/testPost";
        // $url = "https://fcm.googleapis.com/v1/projects/react-cli4/messages";  // not run

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: ' . $authorization
            )
        );

        $result = curl_exec($curl);
        curl_close($curl);
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
