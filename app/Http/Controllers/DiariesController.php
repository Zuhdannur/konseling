<?php namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Helpers;

class DiariesController extends Controller
{

    public function store(Request $request)
    {
        $insert = new \App\Diary;
        $insert->id_user = Auth::user()->id;
        $insert->body = $request->body;
        $insert->title = $request->title;
        $insert->save();
        if($insert){

            $push = new Pusher(
                'e06a6bacb2b9f8503317',
                '865963b7338a3b21359a',
                '786060',
                [
                    'cluster' => 'ap1',
                    'useTLS' => true
                ]
            );

            $data['message'] = "Created Diary";
            $data['sender_id'] = Auth::user()->id;
            $push->trigger('diary','my-event',$data);

            return \Illuminate\Support\Facades\Response::json([
                'message' => 'success',
            ],200);
        }
        return $request;
    }

    public function showMyDiary()
    {

    }

}
