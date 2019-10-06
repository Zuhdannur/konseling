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
                'id' => $insert->id
            ], 200);
        }
        return $request;
    }

    public function remove($id)
    {
        $data = \App\Diary::where('id', $id)->where('id_user', Auth::user()->id)->delete();
        if ($data) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => "Berhasil menghapus data.",
            ], 200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'Gagal menghapus data.'
            ], 201);
        }
    }

    public function removeAll()
    {
        $delete = \App\Diary::where('id_user', Auth::user()->id)->truncate();
        if ($delete) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => "Berremhasil menghapus data."
            ], 200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'Gagal menghapus data.'
            ], 201);
        }
    }

    private function diary(Request $request)
    {
        $datas = \App\Diary::where('id_user', Auth::user()->id)->orderBy('created_at', 'desc');
        
        $paginate = $datas->paginate($request->per_page);

        return $paginate;
    }

    public function all(Request $request)
    {
        $paginate = $this->diary($request);

        return \Illuminate\Support\Facades\Response::json($paginate, 200);
    }

    public function diaryCount(Request $request)
    {
        $total = $this->diary($request)->total();

        return \Illuminate\Support\Facades\Response::json([
            "total" => $total
        ], 200);
    }

    public function readDiary(Request $request)
    {
        $per_page = $request->per_page;

        $mySekolah = \App\User::with('detail')->where('id', Auth::user()->id)->first()->detail;
        $diaries = \App\Diary::whereHas('user', function ($q) use ($mySekolah) {
            $q->whereHas('detail', function ($query) use ($mySekolah) {
                $query->where('id_sekolah', $mySekolah->id_sekolah);
            });
        })->with('user')->with('user.detail')->orderBy('id', 'desc');

        $data = $diaries
        ->paginate($per_page);

        return \Illuminate\Support\Facades\Response::json($data, 200);
    }

    public function put(Request $request)
    {
        $update = \App\Diary::where('id', $request->id)->where('id_user', Auth::user()->id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'tgl' => $request->tgl
        ]);

        if ($update) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'Berhasil menyunting catatan.'
            ], 200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'Gagal menyunting catatan.'
            ], 201);
        }
        return $request;
    }
}
