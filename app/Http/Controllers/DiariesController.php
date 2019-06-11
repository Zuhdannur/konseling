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
        $insert->created_at = $request->created_at;
        $insert->updated_at = $request->updated_at;
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

            $data['message']    = "Created Diary";
            $data['sender_id']  = \App\User::where('id',Auth::user()->id)->first()->name;
            $data['to']         = \App\User::where('id',Auth::user()->id)->with('detail')->first()->detail->school;
            $push->trigger('diary', 'notification', $data);

            return \Illuminate\Support\Facades\Response::json([
                'message' => 'success',
            ], 200);
        }
        return $request;
    }

    public function deleteDiary($id)
    {
        $data = \App\Diary::where('id', $id)->where('id_user', Auth::user()->id)->delete();
        if($data) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => "success",
            ],200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'failed'
            ],201);
        }
    }

    public function showMyDiary()
    {
        $data = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at','desc')->get();
        return \Illuminate\Support\Facades\Response::json([
            "message" => "success",
            "result" => $data
        ], 200);
    }

    public function showMyDiaryToOthers()
    {

        $mySchool = \App\User::with('detail')->where('id',Auth::user()->id)->first()->detail;
        $diaries = \App\Diary::whereHas('user',function ($q) use ($mySchool){
            $q->whereHas('detail',function ($query) use ($mySchool){
                $query->where('school',$mySchool[0]->school);
            });
        })->with('user')->get();

        return \Illuminate\Support\Facades\Response::json([
            "message" => "success",
            "result" => $diaries
        ], 200);
    }

}
