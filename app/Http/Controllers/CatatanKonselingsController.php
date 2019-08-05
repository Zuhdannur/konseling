<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class CatatanKonselingsController extends Controller
{
    public function all(Request $request)
    {
        $catatan = \App\CatatanKonseling::all();
        return Response::json($catatan, 200);
    }
}
