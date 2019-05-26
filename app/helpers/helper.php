<?php namespace App\Helpers;

use Pusher\Pusher;

class Helper
{

    public function sendMessage($message, $notification_type, $receiver)
    {
        $push = new Pusher(
            'e06a6bacb2b9f8503317',
            '865963b7338a3b21359a',
            '786060',
            [
                'cluster' => 'ap1',
                'useTLS' => true
            ]
        );

        $data['message'] = $request->body;
        $data['sender_id'] = $request->sender_id;
        $data['receiver_id'] = $request->reciever_id;

        $push->trigger('my-channel','my-event',$data);
        return true;
    }

}

?>