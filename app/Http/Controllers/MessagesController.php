<?php namespace App\Http\Controllers;

use App\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class MessagesController extends Controller {

//    public function storeRoom(){
//        $room = new \App\Room;
//    }

    public function __construct()
    {
    }

    public function index(){
        $myMessage = \App\Message::where('sender_id',Auth::user()->id)->orWhere('reciever_id',Auth::user()->id)->get();
        return [
            "message"=> "success",
            "result" => $myMessage
        ];
    }

    public function store(Request $request){
        $validation = Validator::make($request->all(),[
            'sender_id' => "require",
            "reciever_id"=>'require'
        ]);
        if($validation){

            $insert = new \App\Message;
            $insert->sender_id = $request->sender_id;
            $insert->reciever_id = $request->reciever_id;
            $insert->body = $request->body;
            $insert->save();
            if($insert){
                $pusher = new Pusher(
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

                $pusher->trigger('my-channel','my-event',$data);
                return [
                    "message" => "success",
                ];
            } else {
                return [
                    "message" => "failed"
                ];
            }
        } else {
            return [
                "message" => $validation->errors()
            ];
        }
    }
}
