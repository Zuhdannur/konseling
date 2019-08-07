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
        // $riwayat = \App\Riwayat::all()->groupBy('schedule_id');
        $riwayat = \App\Riwayat::whereHas('user', function ($q) use ($user) {
            $q->whereHas('detail', function ($query) use ($user) {
                $query->where('id_sekolah', $user->id_sekolah);
            });
        })->with('schedule.consultant')->with('schedule.request')->orderBy('created_at', 'desc');

        $riwayat = $riwayat->whereHas('schedule', function ($q) {
            $q->where('ended', 1);
        });

        if ($request->has('isToday')) {
            if ($request->isToday == 'true') {
                $riwayat = $riwayat->where('created_at', '>=', Carbon::today());
            } else {
                $riwayat = $riwayat->where('created_at', '<', Carbon::today());
            }
        }

        $datas = $riwayat->paginate($request->limit);
        return Response::json($datas, 200);
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
        $datas->with('schedule')->with('user')->with('schedule.consultant')->with('schedule.request');

        if ($request->has('orderBy')) {
            $datas = $datas->orderBy($request->orderBy, 'desc');
        }

        if ($request->has('status')) {
            $datas = $datas->whereHas('schedule', function ($query) use ($request, $datas) {
                if ($request->status == 'selesai') {
                    $query->where('ended', 1);
                }
        
                if ($request->status == 'dibatalkan') {
                    $query->where('canceled', 1);
                }
            });
        }

        $data = $datas
            ->skip($skip)
            ->take($limit)
            ->get();

        $count = $data
            ->paginate($skip)
            ->lastPage($limit);

        return Response::json([
            'total_page' => $count,
            'data' => $data
        ], 200);
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

        if ($filters->has('status')) {
            $datas = $data->whereHas('schedule', function ($query) use ($filters, $data) {
                if ($filters->status == 'selesai') {
                    $query->where('ended', 1);
                }
    
                if ($filters->status == 'dibatalkan') {
                    $query->where('canceled', 1);
                }
            });
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
