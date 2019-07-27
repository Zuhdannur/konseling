<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class RiwayatsController extends Controller
{
    public function all()
    {
        $data = \App\Riwayat::where('user_id', Auth::user()->id)->with('schedule')->with('user')->get();
        return Response::json($data, 200);
    }

    public function get($id)
    {
    }
}
