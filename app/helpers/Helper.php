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
    static protected $API_ACCSESS_KEY = 'AAAA_vRurwA:APA91bGd7ayeeU2Nlb5D0T1DwRc48CzU-G_ez4SM_qIgdGv-wpQvuUhbJ3xbUFmJZOPtr_EVe_vB2z38O4CUjJPY-WcapZb-Xy_Y1rC3B-v-AFIIQsRxMPJi6pZY8jX1k1eytQSdiXiW';

    public function sendMessage($message, $notification_type, $receiver)
    {
        return true;
    }

    public static function sendNotificationTopic($data)
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

        $client = new Client();
        $client->setApiKey(self::$API_ACCSESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $query = \App\User::where(function ($query){
            $query->where('role',"guru");
            $query->whereHas('detail',function ($q){
                $q->where('school',Auth::user()->detail->school);
            });
        })->get();
        $getSchoolId = \App\School::where('school_name',Auth::user()->detail->school)->first()->id;
        $users =[];
        foreach ($query as $value){
            $users[] = $value['firebase_token'];
        }
        $client->addTopicSubscription($getSchoolId, $users);

        $message = new Message();
        $message->setPriority('high');
//        $message->addRecipient(new Device("cQlOvwQ3lu4:APA91bHZiKXMaRYNmsSEx6LojxNrAUzJPKp1LsRJMUaIfxsZ3hu59P8CWhoZWaSz-fnCmETuP34o87whE9NnhFkPGZBnyLt4s8MDT4pk_mrMhdzli95gsjJ3v-_jIyR04Zw2S6KFu4Tm"));
        $message->addRecipient(new Topic('global'));
        $message
            ->setNotification(new Notification(
                $data->title, $data->desc));

        $response = $client->send($message);
//        dd($response->getStatusCode());
        return \response()->json($response);
    }

    public static function sendNotificationToSingel($data)
    {
        $client = new Client();
        $client->setApiKey(self::$API_ACCSESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $firebase_token = \App\User::where('id',$data->id)->first()->firebase_token;

        $message = new Message();
        $message->setPriority('high');
        $message->addRecipient(new Device($firebase_token));
//        $message->addRecipient(new Topic('global'));
        $message
            ->setNotification(new Notification(
                $data->title, $data->body));

        $response = $client->send($message);
//        dd($response->getStatusCode());
        dd($response->getBody()->getContents());
        return \response()->json($response);
    }

}

?>