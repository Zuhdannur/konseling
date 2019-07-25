<?php namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Notification;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Topic;

class Helper
{
    protected static $API_ACCESS_KEY = 'AAAA_vRurwA:APA91bFvUdoT1ruL0WZC3rkvQWoK76WFOgUSAFuc3aUpN0_kjiP22y3Pf_o1TthpfN6_o_0HnHJeMGZMp8MqHzm1zTCk8zuTY4UzAByzknPDlcBlNFvz60oN6fx9Kq3gkfR373aboRy0';

    public function sendMessage($message, $notification_type, $receiver)
    {
        return true;
    }

    public static function sendNotificationTopic($desc)
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

        // $client = new Client();
        // $client->setApiKey(self::$API_ACCSESS_KEY);
        // $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        // $query = \App\User::where(function ($query){
        //     $query->where('role',"guru");
        //     $query->whereHas('detail',function ($q){
        //         $q->where('Sekolah',Auth::user()->detail->Sekolah);
        //     });
        // })->get();
        // $getSekolahId = \App\Sekolah::where('nama_sekolah',Auth::user()->detail->Sekolah)->first()->id;
        // $users =[];
        // foreach ($query as $value){
        //     $users[] = $value['firebase_token'];
        // }
        // $client->addTopicSubscription($getSekolahId, $users);

        $client = new Client();
        $client->setApiKey(self::$API_ACCESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $message = new Message();
        $message->setPriority('normal');
        $pattern = "guru".Auth::user()->detail->id_sekolah."pengajuan";

//        $message->addRecipient(new Device("cQlOvwQ3lu4:APA91bHZiKXMaRYNmsSEx6LojxNrAUzJPKp1LsRJMUaIfxsZ3hu59P8CWhoZWaSz-fnCmETuP34o87whE9NnhFkPGZBnyLt4s8MDT4pk_mrMhdzli95gsjJ3v-_jIyR04Zw2S6KFu4Tm"));
        $message->addRecipient(new Topic($pattern));
        $message->setNotification(new Notification('Kamu mendapatkan pengajuan baru', "Pengajuan dari siswa bernama ".Auth::user()->name));
        
        $response = $client->send($message);
        return \response()->json($response);
    }

    public static function sendNotificationToSingle($result)
    {
        $title = $result['title'];
        $body = $result['body'];
        $type = $result['type'];
        $id = $result['requester_id'];
        $read = $result['read'];

        $client = new Client();
        $client->setApiKey(self::$API_ACCESS_KEY);
        $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

        $firebase_token = \App\User::where('id', $id)->first()->firebase_token;

        $message = new Message();
        $message->setPriority('normal');
        $message->addRecipient(new Device($firebase_token));
        $message->setNotification(new Notification($title, $body));

        $notif = new \App\Notification;
        $notif->id_user = $id;
        $notif->title = $title;
        $notif->body = $body;
        $notif->type = $type;
        $notif->read = $read;
        $notif->save();

        $message->setData([
            'title' => $title,
            'body' =>  $body,
            'type' => $type,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'read' => $read
        ]);

        $response = $client->send($message);
        return \response()->json($response);
    }

    // public static function storeDataNotification($notification)
    // {
    //     $client = new Client();
    //     $client->setApiKey(self::$API_ACCESS_KEY);
    //     $client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

    //     $firebase_token = \App\User::where('id', $notification['requester_id'])->first()->firebase_token;

    //     $message = new Message();
    //     $message->setPriority('normal');
    //     $message->addRecipient(new Device($firebase_token));
    //     //LocalBroadcast
    //     $title = $notification['title'];
    //     $body = $notification['body'];

    //     $notif = new \App\Notification;
    //     $notif->id_user = $notification['id_user'];
    //     $notif->title = $title;
    //     $notif->body = $body;
    //     $notif->type = $notification['type'];
    //     $notif->save();

    //     $message->setData([
    //         'title' => $notif->title,
    //         'body' =>  $notif->$body,
    //         'type' => $notif->type,
    //         'created_at' => Carbon::now()->toDateTimeString(),
    //         'updated_at' => Carbon::now()->toDateTimeString()
    //     ]);

    //     $response = $client->send($message);
    //     return \response()->json($response);
    // }
}
