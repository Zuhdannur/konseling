<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Http\Request;

class RiwayatsController extends Controller
{
    public function all(Request $filters)
    {
        $data = \App\Riwayat::where('user_id', Auth::user()->id);
        $data->with('schedule')->with('user')->get();

        $limit = $filters->limit;
        if (empty($filters->page)) {
            $skip = 0;
        } else {
            $skip = $limit * $filters->page;
        }
        $datas = $data
            ->skip($skip)
            ->take($limit);

        return Response::json($datas, 200);
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

    public function get($id)
    {
    }
}
