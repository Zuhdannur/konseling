<?php namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ArtikelsController extends Controller
{

    public function getTitle()
    {
        $data = \App\Artikel::select('title', 'id')->get();
        return \Illuminate\Support\Facades\Response::json([
            "message" => 'success',
            "result" => $data
        ], 200);
    }

    public function create(Request $request)
    {
        $insert = new \App\Artikel;
//        $insert->img = $request->file
        $insert->title = $request->title;
        $insert->desc = $request->desc;
        $insert->save();
        if ($insert) {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'success'
            ], 200);
        } else {
            return \Illuminate\Support\Facades\Response::json([
                "message" => 'failed'
            ], 201);
        }
    }

    public function getRelatedArtikel(Request $request)
    {
        $data = \App\Artikel::where('title','LIKE','%'. $request->title .'%')->get();
        return \Illuminate\Support\Facades\Response::json([
            "message" => 'success',
            "result"  => $data
        ],200);
    }

}
