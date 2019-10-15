<?php


namespace App\Repositories;


use App\Diary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DiaryRepository
{
    public function all(Request $request) {
        $query = Diary::where('id_user', Auth::user()->id);
        if($request->has('orderBy')) {
            $query = $query->orderBy($request->orderBy, 'desc');
        }
        $paginate = $query->paginate($request->per_page);

        return Response::json($paginate, 200);
    }

    public function add(Request $request) {
        $insert = new Diary;
        $insert->id_user = Auth::user()->id;
        $insert->body = $request->body;
        $insert->title = $request->title;
        $insert->tgl = $request->tgl;
        $insert->save();
        if ($insert) {
            return Response::json([
                'message' => 'success',
                'id' => $insert->id
            ], 200);
        }
        return $request;
    }

    public function remove($id)
    {
        $data = Diary::where('id', $id)->where('id_user', Auth::user()->id)->delete();
        if ($data) {
            return Response::json([
                "message" => "Berhasil menghapus data.",
            ], 200);
        } else {
            return Response::json([
                "message" => 'Gagal menghapus data.'
            ], 201);
        }
    }

    public function update(Request $request)
    {
        $update = Diary::where('id', $request->id)->where('id_user', Auth::user()->id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'tgl' => $request->tgl
        ]);

        if ($update) {
            return Response::json([
                "message" => 'Berhasil menyunting catatan.'
            ], 200);
        } else {
            return Response::json([
                "message" => 'Gagal menyunting catatan.'
            ], 201);
        }
        return $request;
    }


    /*For Teacher*/
    public function readDiary(Request $request)
    {
        $per_page = $request->per_page;

        $mySekolah = User::with('detail')->where('id', Auth::user()->id)->first()->detail;
        $diaries = Diary::whereHas('user', function ($q) use ($mySekolah) {
            $q->whereHas('detail', function ($query) use ($mySekolah) {
                $query->where('id_sekolah', $mySekolah->id_sekolah);
            });
        })->with('user')->with('user.detail')->orderBy('id', 'desc');

        $data = $diaries
            ->paginate($per_page);

        return Response::json($data, 200);
    }

    public function diaryCount(Request $request) {
        $datas = Diary::where('id_user', Auth::user()->id)->orderBy('created_at', 'desc');

        $total = $datas->paginate($request->per_page)->total();

        return \Illuminate\Support\Facades\Response::json([
            "total" => $total
        ], 200);
    }
}
