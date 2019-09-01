<?php namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Http\Request;
use DB;

class ArtikelsController extends Controller
{
    public $searchQuery = "SELECT 
                        exists(select 1 from `favorite` fav where fav.id_artikel = p.id_artikel and fav.id_user = u.id limit 1) as bookmarked
                        , u.nama
                        , p.id_artikel
                        FROM
                        user u,
                        artikel p
                        WHERE
                        u.id = 2 AND
                        p.nama_artikel LIKE '%GurindaM%'";

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
        // $datas = \App\Artikel::where(function ($q) use ($request) {
        //     $q->whereRaw('LOWER(title) LIKE ? ', '%' . strtolower($request->title) . '%');
        // });

        $bookmark = "exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = p.id and fav.id_user = u.id limit 1) as hasBookmark";
        // $categorias = \App\Favorit::with(['artikel' => function($query) use ($bookmark){
        //     $query->select($bookmark, 'tbl_user.name', 'tbl_artikel.title')
        // }])->whereRaw('u.id =:id', ['id' => 1])->get();

        // $data = DB::selectOne(
        //     'SELECT exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = tbl_artikel.id and fav.id_user = tbl_user.id limit 1) as bookmarked FROM tbl_artikel, tbl_user'
        // );
        $data = \App\Favorite::with(['artikel','user'], function ($q) use ($request) {
            $q->select(array(
                DB::raw('exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = artikel.id and fav.id_user = user.id limit 1) as hasBookmark')
                ,'user.name'
                ,'artikel.title'
            ))->whereRaw('tbl_user.id:=id', ['id' => 1])
            ->whereRaw('tbl_artikel.title LIKE ? ', '%' . strtolower($request->title) . '%');
        })->get();
        // WHERE u.id =:id AND p.title LIKE :q", ['id' => 1, 'q' => '%'.$request->title.'%']);
        // $data = DB::select(
        //     "exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = p.id and fav.id_user = u.id limit 1) as hasBookmark",
        //     "u.name",
        //     "p.id",
        //     "p.title",
        //     "p.desc"
        // )->from('tbl_user u', 'tbl_artikel p');

        // $data = DB::table('tbl_artikel')
        //     ->select(array(
        //         DB::raw('exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = tbl_artikel.id and fav.id_user = tbl_user.id limit 1) as hasBookmark')
        //         ,'tbl_user.name'
        //         ,'tbl_artikel.title'
        //     ))->from('tbl_user')->get();

        
        
        // $data = DB::select("
        //     SELECT
        //     exists(select 1 from tbl_fav_artikel fav where fav.id_artikel = p.id and fav.id_user = u.id limit 1) as hasBookmark
        //     ,u.name
        //     ,p.id
        //     ,p.title
        //     ,p.desc
        //     FROM
        //     tbl_user u,
        //     tbl_artikel p WHERE u.id =:id AND p.title LIKE :q", ['id' => 1, 'q' => '%'.$request->title.'%']);

        // ->whereRaw('tbl_user.id:=id', ['id' => 1]);
        // ->whereRaw('tbl_artikel.title LIKE ? ', '%' . strtolower($request->title) . '%');
        // WHERE
        // u.id=:id AND
        // p.title LIKE '%gurindam%'
        // ORDER BY p.created_at DESC", ['id' => 1]);

        // $data = DB::select("SELECT * FROM tbl_user WHERE tbl_user.id =:id", ['id' => 1]);

        // $data = $datas
        // ->skip($skip)
        // ->take($limit)
        // ->get();
        // $pagination = new \Illuminate\Pagination\Paginator($data, $request->per_page);
        return \Illuminate\Support\Facades\Response::json($data, 200);
    }

//     public function getRelatedArtikelCount(Request $request)
//     {
//         $limit = $request->limit;

//         if ($request->page == "") {
//             $skip = 0;
//         } else {
//             $skip = $limit * $request->page;
//         }

    // //        $data = \App\Artikel::where('LOWER(`title`)','LIKE','%'.strtolower($request->title).'%')->get();
//         $datas = \App\Artikel::where(function ($q) use ($request) {
//             $q->whereRaw('LOWER(title) LIKE ? ', '%' . strtolower($request->title) . '%');
//         });

//         $datas = \App\Artikel::selectRaw('')

//         $count = $datas
//         ->paginate($limit)
//         ->lastPage();

//         return \Illuminate\Support\Facades\Response::json([
//             "total_page" => $count
//         ], 200);
//     }

    public function storeFavorite(Request $request)
    {
        if ($this->checkingArtikel($request->id_artikel)) {
            return \response()->json([
                "message" => "duplicate artikel"
            ], 202);
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

        if ($request->page == "") {
            $skip = 0;
        } else {
            $skip = $limit * $request->page;
        }

        $datas = \App\Favorite::where('id_user', Auth::user()->id)->with('artikel');
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

        if ($request->page == "") {
            $skip = 0;
        } else {
            $skip = $limit * $request->page;
        }
        $datas = \App\Favorite::where('id_user', Auth::user()->id)->with('artikel');

        $count = $datas
        ->paginate($limit)
        ->lastPage();

        return \Illuminate\Support\Facades\Response::json([
            "total_page" => $count
        ], 200);
    }

    public function checkingArtikel($id)
    {
        $check = \App\Favorite::where([['id_user',Auth::user()->id],['id_favorit',$id]])->get();
        if (count($check) > 0) {
            return true;
        } else {
            return false;
        }
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
