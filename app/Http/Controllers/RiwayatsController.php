<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class RiwayatsController extends Controller
{
    public function all()
    {
        $data = \App\Riwayat::all();
        return Response::json($data, 200);
    }
}
