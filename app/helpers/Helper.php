<?php namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;

class Helper
{

    public function sendMessage($message, $notification_type, $receiver)
    {
        return true;
    }

    public static function sendNotification($data)
    {
//        $API_ACCSESS_KEY = 'AAAA_vRurwA:APA91bH6PpT6Uv6xEY1Z_3FC1vQefwYH6QbjQQ5l5kjxsZJOxzmZeakfR-9YbY-7-lCuBxx6neXph7zf_gxVxXDepW3pETJTpTGucualxk6e2k_evTRlqr2E3EEpm63Eaa7IgZVyEZ0O';
//        $msg = array
//        (
//            'body'   => 'msg',
//            'title'     => 'title',
//            'key1'  => 'val1'
//        );
//        $fields = array(
//            'to' => '/topics/global',
//            'notification' => $msg
//        );
//        $header = [
//            'Authorization: key='. $API_ACCSESS_KEY,
//            'Content-Type: application/json'
//        ];
//
//        $crul = curl_init();
//        curl_setopt($crul,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($crul,CURLOPT_POST,true);
//        curl_setopt( $crul,CURLOPT_HTTPHEADER, $header );
//        curl_setopt( $crul,CURLOPT_RETURNTRANSFER, true );
//        curl_setopt( $crul,CURLOPT_SSL_VERIFYPEER, false );
//        curl_setopt( $crul,CURLOPT_POSTFIELDS, json_encode( $fields ) );
//        $result = curl_exec($crul );
//        if($result == FALSE){
//            return response()->json(["Curl Failed "=>curl_error($crul)]);
//        }
//        curl_close( $crul );
//        return response($result,200);
        $API_ACCSESS_KEY = 'AAAA_vRurwA:APA91bGd7ayeeU2Nlb5D0T1DwRc48CzU-G_ez4SM_qIgdGv-wpQvuUhbJ3xbUFmJZOPtr_EVe_vB2z38O4CUjJPY-WcapZb-Xy_Y1rC3B-v-AFIIQsRxMPJi6pZY8jX1k1eytQSdiXiW';
        $client = new Client();
        $client->setApiKey($API_ACCSESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());
        dd(Auth::user()->detail->school);
        $client->addTopicSubscription("1",array(Auth::user()->firebase_token));

        $message = new Message();
        $message->setPriority('high');
//        $message->addRecipient(new Device("cQlOvwQ3lu4:APA91bHZiKXMaRYNmsSEx6LojxNrAUzJPKp1LsRJMUaIfxsZ3hu59P8CWhoZWaSz-fnCmETuP34o87whE9NnhFkPGZBnyLt4s8MDT4pk_mrMhdzli95gsjJ3v-_jIyR04Zw2S6KFu4Tm"));
        $message->addRecipient(new Topic('global'));
        $message
            ->setNotification(new Notification(
                "Bismillah","hai"));

        $response = $client->send($message);
//        dd($response->getStatusCode());
        dd($response->getBody()->getContents());
        return \response()->json($response);
    }

}

?>