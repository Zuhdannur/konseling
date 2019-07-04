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

//            $push = new Pusher(
//                'e06a6bacb2b9f8503317',
//                '865963b7338a3b21359a',
//                '786060',
//                [
//                    'cluster' => 'ap1',
//                    'useTLS' => true
//                ]
//            );
//
//            $data['message']    = "Created Diary";
//            $data['sender_id']  = \App\User::where('id',Auth::user()->id)->first()->name;
//            $data['to']         = \App\User::where('id',Auth::user()->id)->with('detail')->first()->detail->school;
//            $push->trigger('diary', 'notification', $data);

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

    public function showMyDiary(Request $request)
    {
        $datas = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at','desc')->get();
        return \Illuminate\Support\Facades\Response::json(
            $datas
        , 200);
    }

    public function updateDiary(Request $request)
    {
        $update = \App\Diary::where('id', $request->id)->where('id_user', Auth::user()->id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'tgl' => $request->tgl,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
        ]);

        if($update) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'diary updated'
            ],200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'failed to update'
            ],201);
        }
        return $request;
    }

    public function showMyDiaryToOthers(Request $request)
    {
        $perPage = 15;

        if ($request->pPage == "") {
            $skip = 0;
        }
        else {
            $skip = $perPage * $request->pPage;
        }

        $mySchool = \App\User::with('detail')->where('id',Auth::user()->id)->first()->detail;
        $diaries = \App\Diary::whereHas('user',function ($q) use ($mySchool){
            $q->whereHas('detail',function ($query) use ($mySchool){
                $query->where('school',$mySchool->school);
            });
        })->with('user');

        $data = $diaries
        ->skip($skip)
        ->take($perPage)
        ->get();

        $count = $diaries
        ->paginate($perPage)
        ->getTotal();

        return \Illuminate\Support\Facades\Response::json([
            "data" => $diaries,
            "total" => $count
        ], 200);
    }

}
