<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Http\Request;

class RiwayatsController extends Controller
{
    public function all(Request $filters)
    {
        $limit = $filters->limit;
        if (empty($filters->page)) {
            $skip = 0;
        } else {
            $skip = $limit * $filters->page;
        }

        $datas = \App\Riwayat::where('user_id', Auth::user()->id);
        // $datas = $datas->with('schedule')->with('user');
        dd("Skip ".$skip." Take ".$limit);

        $data = $datas
            ->skip($skip)
            ->take($limit)
            ->get();

        return Response::json($data, 200);
    }

    public function count(Request $filters)
    {
        $data = \App\Riwayat::where('user_id', Auth::user()->id);

        $limit = $filters->limit;
        if (empty($filters->page)) {
            $skip = 0;
        } else {
            $skip = $limit * $filters->page;
        }
        $count = $data
            ->paginate($skip)
            ->lastPage($limit);

        return Response::json(['total_page' => $count], 200);
    }

    public function remove($id)
    {
        $data = \App\Riwayat::where('id', $id)->where('user_id', Auth::user()->id)->delete();
        if ($data) {
            return Response::json(['message' => 'Berhasil menghapus pengajuan.'], 200);
        } else {
            return Response::json(['message' => 'Gagal menghapus pengajuan.'], 201);
        }
    }
}
