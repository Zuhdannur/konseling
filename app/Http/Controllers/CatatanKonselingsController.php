<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class CatatanKonselingsController extends Controller
{
    public function all(Request $request)
    {
        $catatan = \App\CatatanKonseling::all();
        return Response::json($catatan, 200);
    }
}
