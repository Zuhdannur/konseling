<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class KelasController extends Controller
{
    public function all()
    {
        $data = \App\Kelas::all();
        return Response::json([
            "message" => "success",
            "result" => $data
        ], 200);
    }

    public function add(Request $request)
    {
        $insert = new \App\Kelas;
        $insert->nama_kelas = $request->nama_kelas;
        $insert->save();
        if ($insert) {
            return Response::json([
                "message" => "successfuly"
            ], 200);
        } else {
            return Response::json([
                "message" => "failed"
            ], 201);
        }
    }
}
