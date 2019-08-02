<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RiwayatsController extends Controller
{
    public function view(Request $request)
    {
        $user = \App\User::with('detail')->where('id', Auth::user()->id)->first()->detail;
        $riwayat = \App\Riwayat::whereHas('user', function ($q) use ($user) {
            $q->whereHas('detail', function ($query) use ($user) {
                $query->where('id_sekolah', $user->id_sekolah);
            });
        })->with('schedule.consultant')->with('schedule.request');

        if ($request->has('isToday')) {
            if ($request->isToday == 'true') {
                $riwayat = $riwayat->where('created_at', Carbon::today());
            } else {
                $riwayat = $riwayat->where('created_at', '<', Carbon::today());
            }
        }

        $data = $riwayat->groupBy('schedule_id')->get();
        return Response::json($data, 200);
    }

    public function all(Request $request)
    {
        $limit = $request->limit;
        if (empty($request->page)) {
            $skip = 0;
        } else {
            $skip = $limit * $request->page;
        }

        $datas = \App\Riwayat::where('user_id', Auth::user()->id);
        $datas->with('schedule')->with('user')->with('schedule.consultant');

        if ($request->has('orderBy')) {
            $datas = $datas->orderBy($request->orderBy, 'desc');
        }

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
