<?php
namespace App\Http\Controllers;

interface NotificationControllerInterface{
    const CONTROLLER_NAME = 'NotificationController';
    const PREFIX = 'notification';
    const REGISTER_FCM = 'registerFcm';
    const PUSH_NOTIFICATION = 'pushNotification';

    public function registerFcm();

    public function pushNotification();
}
