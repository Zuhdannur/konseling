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
//        $data = \App\Artikel::where('LOWER(`title`)','LIKE','%'.strtolower($request->title).'%')->get();
        $data = \App\Artikel::where(function ($q) use ($request) {
            $q->whereRaw('LOWER(title) LIKE ? ', '%' . strtolower($request->title) . '%');
        })->get();
        return \Illuminate\Support\Facades\Response::json([
            "message" => 'success',
            "result" => $data
        ], 200);
    }

    public function storeFavorite(Request $request)
    {
        $insert = new \App\Favorite;
        $insert->id_artikel = $request->id_artikel;
        $insert->id_user = Auth::user()->id;
        $insert->save();

        if ($insert) {
            return \response()->json([
                "message" => "success"
            ], 200);
        } else {
            return \response()->json([
                "message" => "failed"
            ], 200);
        }
    }

    public function getMyFavorite()
    {
        $data = \App\Favorite::where('id_user', Auth::user()->id)->with('artikel')->get();
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = $value['artikel'];
            $result[$key]['id_favorit'] = $value->id_favorit;
			$result[$key]['id_user] = Auth::user()->id;
        }
//        $data['result'] =
        return \response()->json($result, 200);
    }

    public function removeMyFavorit($id)
    {
        $delete = \App\Favorite::find($id)->delete();
        if($delete){
            return \response([
                "message" => "succsess"
            ],200);
        } else {
            return \response([
                "message" => "failed"
            ],201);
        }
    }
}
