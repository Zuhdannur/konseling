<?php


namespace App\Repositories;


use App\Diary;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class DiaryRepository
{

    private $diary;
    private $user;

    /**
     * DiaryRepository constructor.
     * @param $diary
     */
    public function __construct(Diary $diary, User $user)
    {
        $this->diary = $diary;
        $this->user = $user;
    }


    public function all(Request $request)
    {
        $query = $this->diary->where('user_id', Auth::user()->id);
        if ($request->has('orderBy')) {
            $query = $query->orderBy($request->orderBy, 'desc');
        }
        $paginate = $query->paginate($request->per_page);

        return Response::json($paginate, 200);
    }

    public function add(Request $request)
    {
        $insert = $this->diary;
        $insert->user_id = Auth::user()->id;
        $insert->body = $request->body;
        $insert->title = $request->title;
        $insert->tgl = $request->tgl;
        $insert->save();

        return Response::json([
            'message' => 'success',
            'id' => $insert->id
        ], 200);
    }

    public function remove($id)
    {
        $data = $this->diary->where('id', $id)->where('user_id', Auth::user()->id)->delete();
        if (!$data) {
            return Response::json([
                "message" => 'Gagal menghapus data.'
            ], 201);
        }
        return Response::json([
            "message" => "Berhasil menghapus data.",
        ], 200);
    }

    public function update(Request $request)
    {
        $update = $this->diary->where('id', $request->id)->where('user_id', Auth::user()->id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'tgl' => $request->tgl
        ]);

        if (!$update) {
            return Response::json([
                "message" => 'Gagal menyunting catatan.'
            ], 201);
        }

        return Response::json([
            "message" => 'Berhasil menyunting catatan.'
        ], 200);
    }


    /*For Teacher*/
    public function readDiary(Request $request)
    {
        $per_page = $request->per_page;

        $mySekolah = $this->user->with('detail')->where('id', Auth::user()->id)->first()->detail;
        $diaries = $this->diary->whereHas('user', function ($q) use ($mySekolah) {
            $q->whereHas('detail', function ($query) use ($mySekolah) {
                $query->where('sekolah_id', $mySekolah->sekolah_id);
            });
        })->with('user')->with('user.detail')->orderBy('id', 'desc');

        $data = $diaries->paginate($per_page);

        return Response::json($data, 200);
    }

    public function diaryCount($id)
    {
        $total = $this->diary->where('user_id', $id)->count();

        return Response::json([
            "total" => $total
        ], 200);
    }


}
