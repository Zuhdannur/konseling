<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MastersController extends Controller
{
    public function getListSekolah()
    {
        $data = \App\Sekolah::all();
        return Response::json([
            "message" => "success",
            "result" => $data
        ], 200);
    }

    // public function getListClass($id)
    // {
    //     $data = \App\Kelas::where('id_Sekolah', $id)->get();
    //     return Response::json([
    //         "message" => 'success',
    //         "result" => $data
    //     ], 200);
    // }

    public function storeSekolah(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sekolah' => 'required',
            'address' => 'required'
        ]);
        if ($validator) {
            $insert = new \App\Sekolah;
            $insert->nama_sekolah = $request->nama_sekolah;
            $insert->address = $request->address;
            $insert->save();
            if ($insert) {
                return Response::json([
                    "message" => "success"
                ], 200);
            } else {
                return Response::json([
                    "message" => "failed"
                ], 201);
            }
        } else {
            return [
                "message" => $validator->errors()
            ];
        }
    }

    public function destroySekolah($id)
    {
        $delete = \App\Sekolah::find($id)->delete();
        if ($delete) {
            return Response::json([
                "message" => 'success'
            ], 200);
        } else {
            return Response::json([
                "message" => 'failed'
            ], 201);
        }
    }
}
