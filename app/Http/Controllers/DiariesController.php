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
        $insert->tgl = $request->tgl;
        $insert->save();
        if ($insert) {

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
            $push->trigger('diary', 'my-event', $data);

            return \Illuminate\Support\Facades\Response::json([
                'message' => 'success',
            ], 200);
        }
        return $request;
    }

    public function showMyDiary()
    {
        $data = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at','desc')->get();
        return \Illuminate\Support\Facades\Response::json([
            "message" => "success",
            "result" => $data
        ], 200);
    }

    public function showMyDiaryToOthers($id)
    {
        $find = \App\User::with('detail')->where('id',$id)->first();
        $myData = \App\User::with('detail')->where('id',Auth::user()->id)->first();
        if($find->school == $myData->school){

            $data = \App\Diary::where('id_user', $id)->get();

            return \Illuminate\Support\Facades\Response::json([
                "message" => "success",
                "result" => $data
            ], 200);

        }

        return \Illuminate\Support\Facades\Response::json([
            "message" => 'Failed',
        ],201);
    }

}
