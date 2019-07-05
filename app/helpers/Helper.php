<?php namespace App\Helpers;

use Pusher\Pusher;

class Helper
{

    public function sendMessage($message, $notification_type, $receiver)
    {
        return true;
    }

    public static function sendNotification($data)
    {
        $API_ACCSESS_KEY = 'AAAA_vRurwA:APA91bH6PpT6Uv6xEY1Z_3FC1vQefwYH6QbjQQ5l5kjxsZJOxzmZeakfR-9YbY-7-lCuBxx6neXph7zf_gxVxXDepW3pETJTpTGucualxk6e2k_evTRlqr2E3EEpm63Eaa7IgZVyEZ0O';
        $message = [
            "message" => $data['message'],
        ];
        $fields = array(
            'to' => $data['to'],
            'data' => $message
        );
        $header = [
            'Authorization: key='. $API_ACCSESS_KEY,
            'Content-Type: application/json'
        ];

        $crul = curl_init();
        curl_setopt($crul,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
        curl_setopt($crul,CURLOPT_POST,true);
        curl_setopt( $crul,CURLOPT_HTTPHEADER, $header );
        curl_setopt( $crul,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $crul,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $crul,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($crul );
        curl_close( $crul );
        return $result;
    }

}

?>