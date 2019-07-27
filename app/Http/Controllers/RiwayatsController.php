<?php namespace App\Http\Controllers;

class RiwayatsController extends Controller
{
    public function all()
    {
        $data = \App\Riwayat::all();
        return Response::json($data, 200);
    }
}
