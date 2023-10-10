<?php

namespace App\Http\Controllers;

use App\Helper\HelperFunction;
use App\Models\Notification;
use App\Models\Paper;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notification;
    protected $helperFunction;

    function __construct(Notification $notification, HelperFunction $helperFunction)
    {
        $this->notification = $notification;
        $this->helperFunction = $helperFunction;
    }

    //
    public function registerFcm(Request $request) {
        $notification = $this->notification;
        $params_content = json_decode($request->getContent(), true);
        if ($params_content) {
            $check_notification = $notification->where("deviceId", "=", $params_content['deviceId'])->first();
            if ($check_notification) {
                $check_notification->fill([
                    "fcmToken" => $params_content["fcmToken"],
                    "deviceId" => $params_content["deviceId"],
                    "active" => $params_content["active"]
                ])->save();
                return [
                    "message" => "add fcm token seccess",
                    "code" => 200,
                    "data" => $check_notification->toArray() 
                ];
            }
            $save_value = $notification->fill([
                "fcmToken" => $params_content["fcmToken"],
                "deviceId" => $params_content["deviceId"],
                "active" => $params_content["active"]
            ])->save();
            if ($save_value) {
                return [
                    "message" => "add fcm token seccess",
                    "code" => 200,
                    "data" => $notification->toArray() 
                ];
            }
        }
        
        return($request->getContent());
    }

    function push_notification() {
        $paper = Paper::all()->last();
        $all_fcm = $this->notification->where("active", true)->get()->toArray();
        $this->helperFunction->push_notification_json($all_fcm, $paper);
        return true;
    }
}
