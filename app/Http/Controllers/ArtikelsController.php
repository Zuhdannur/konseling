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
        if($this->checkingArtikel($request->id_artikel)){
            return \response()->json([
                "message" => "duplicate artikel"
            ]);
        }
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

    public function getMyFavorite(Request $request)
    {
        $limit = $request->limit;

        if ($request->pPage == "") {
            $skip = 0;
        }
        else {
            $skip = $limit * $request->pPage;
        }

        $datas = \App\Favorite::where('id_user', Auth::user()->id)->with('artikel')->get();
        $data = $datas
        ->skip($skip)
        ->take($limit)
        ->get();

        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = $value['artikel'];
            $result[$key]['id_favorit'] = $value->id_favorit;
			$result[$key]['id_user'] = Auth::user()->id;
        }
        $data['result'] = \response()->json($result, 200);
        return $data['result'];
    }

    public function getMyFavoriteCount(Request $request)
    {
        $limit = $request->limit;

        if ($request->pPage == "") {
            $skip = 0;
        }
        else {
            $skip = $limit * $request->pPage;
        }
        $datas = \App\Favorite::where('id_user', Auth::user()->id)->with('artikel')->get();

        $count = $datas
        ->paginate($limit)
        ->lastPage();

        return \Illuminate\Support\Facades\Response::json([
            "total_page" => $count
        ],200);
    }

    public function checkingArtikel($id)
    {
        $check = \App\Favorite::where([['id_user',Auth::user()->id],['id_favorit',$id]])->get();
        if(count($check) > 0)return true;
        else return false;
    }

    public function removeMyFavorit($id)
    {
        $delete = \App\Favorite::find($id)->delete();
        if ($delete) {
            return \response([
                "message" => "succsess"
            ], 200);
        } else {
            return \response([
                "message" => "failed"
            ], 201);
        }
    }
}
