<?php namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Helpers;

class DiariesController extends Controller
{

    public function add(Request $request)
    {
        $insert = new \App\Diary;
        $insert->id_user = Auth::user()->id;
        $insert->body = $request->body;
        $insert->title = $request->title;
        $insert->tgl = $request->tgl;
        $insert->save();
        if ($insert) {
            return \Illuminate\Support\Facades\Response::json([
                'message' => 'success',
            ], 200);
        }
        return $request;
    }

    public function remove($id)
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

    public function all(Request $request)
    {
        $limit = $request->limit;

        if ($request->page == "") {
            $skip = 0;
        }
        else {
            $skip = $limit * $request->page;
        }
        $datas = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at','desc');

        $data = $datas
        ->skip($skip)
        ->take($limit)
        ->get();

        return \Illuminate\Support\Facades\Response::json(
            $data
        , 200);
    }

    public function diaryCount(Request $request)
    {
        $limit = $request->limit;

        if ($request->page == "") {
            $skip = 0;
        }
        else {
            $skip = $limit * $request->page;
        }
        $datas = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at','desc');

        $count = $datas
        ->paginate($limit)
        ->lastPage();

        return \Illuminate\Support\Facades\Response::json([
            "total_page" => $count
        ],200);
    }

    public function put(Request $request)
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

    public function sharedDiaryCount(Request $request) {
        $limit = $request->limit;

        if ($request->page == "") $skip = 0;
        else $skip = $limit * $request->page;
       
        $mySchool = \App\User::with('detail')->where('id', $id)->first()->detail;
        $diaries = \App\Diary::whereHas('user',function ($q) use ($mySchool){
            $q->whereHas('detail',function ($query) use ($mySchool){
                $query->where('id_sekolah', $mySchool->id_sekolah);
            });
        })->with('user');
        $count = $diaries
        ->paginate($limit)
        ->lastPage();

        return \Illuminate\Support\Facades\Response::json([
            "total_page" => $count
        ], 200);
    }

    public function sharedDiary(Request $request)
    {
        $limit = $request->limit;

        if ($request->page == "") $skip = 0;
        else $skip = $limit * $request->page;
       
        $mySchool = \App\User::with('detail')->where('id', $id)->first()->detail;
        $diaries = \App\Diary::whereHas('user',function ($q) use ($mySchool){
            $q->whereHas('detail',function ($query) use ($mySchool){
                $query->where('id_sekolah',$mySchool->id_sekolah);
            });
        })->with('user');

        $data = $diaries
        ->skip($skip)
        ->take($limit)
        ->get();

        return \Illuminate\Support\Facades\Response::json($data, 200);
    }
}
