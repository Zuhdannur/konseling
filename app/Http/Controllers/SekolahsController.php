<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SekolahsController extends Controller
{
    public function all()
    {
        $data = \App\Sekolah::all();
        return Response::json($data, 200);
    }

    public function get($id)
    {
        $data = \App\Sekolah::find($id)->get();
        return Response::json($data, 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sekolah' => 'required',
            'alamat' => 'required'
        ]);


        if ($validator) {
            $insert = new \App\Sekolah;
            // $insert->nama_sekolah = $request->nama_sekolah;
    
            // Replace apabila nama sudah ada
            $insert::updateOrCreate([
                'nama_sekolah' => $request->nama_sekolah
            ], [
                'alamat' => $request->alamat
            ]);

            if ($insert) {
                return Response::json([
                    "message" => "berhasil menambahkan."
                ], 200);
            } else {
                return Response::json([
                    "message" => "gagal menambahkan."
                ], 201);
            }
        } else {
            return [
                "message" => $validator->errors()
            ];
        }
    }

    public function put($id, Request $request)
    {
        $update = \App\Sekolah::find($id)->update([
            "nama_sekolah" => $request->nama_sekolah,
            "alamat" => $request->alamat
        ]);
        if ($update) {
            return Response::json([ "message" => "berhasil menyunting." ], 200);
        } else {
            return Response::json([ "message" => "gagal menyunting." ], 201);
        }
    }

    public function remove($id)
    {
        $delete = \App\Sekolah::find($id)->delete();
        if ($delete) {
            return Response::json(["message" => 'berhasil hapus.'], 200);
        } else {
            return Response::json(["message" => 'gagal menghapus.'], 201);
        }
    }
}
