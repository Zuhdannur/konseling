<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SekolahsController extends Controller
{
    public function all()
    {
        $data = \App\School::all();
        return Response::json($data, 200);
    }

    public function get($id)
    {
        $data = \App\School::find($id)->get();
        return Response::json($data, 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required',
            'address' => 'required'
        ]);


        if ($validator) {
            $insert = new \App\School;
            // $insert->school_name = $request->school_name;
    
            // Replace apabila nama sudah ada
            $insert::updateOrCreate([
                'school_name' => $request->school_name
            ], [
                'address' => $request->address
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
        $update = \App\School::find($id)->update([
            "school_name" => $request->school_name,
            "address" => $request->address
        ]);
        if ($update) {
            return Response::json([ "message" => "berhasil menyunting." ], 200);
        } else {
            return Response::json([ "message" => "gagal menyunting." ], 201);
        }
    }

    public function remove($id)
    {
        $delete = \App\School::find($id)->delete();
        if ($delete) return Response::json(["message" => 'berhasil hapus.'], 200);
        else return Response::json(["message" => 'gagal menghapus.'], 201);
    }
}
