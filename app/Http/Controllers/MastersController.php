<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MastersController extends Controller
{
    public function getListSchool()
    {
        $data = \App\School::all();
        return Response::json([
            "message" => "success",
            "result" => $data
        ], 200);
    }

    // public function getListClass($id)
    // {
    //     $data = \App\Kelas::where('id_school', $id)->get();
    //     return Response::json([
    //         "message" => 'success',
    //         "result" => $data
    //     ], 200);
    // }

    public function storeSchool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required',
            'address' => 'required'
        ]);
        if ($validator) {
            $insert = new \App\School;
            $insert->school_name = $request->school_name;
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

    public function destroySchool($id)
    {
        $delete = \App\School::find($id)->delete();
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
