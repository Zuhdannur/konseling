<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller {

    public function all() {
        $data = \App\Kelas::all();
        return Response::json([
            "message" => "success",
            "result" => $data
        ],200);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_school' => 'required',
            'class_name' => 'required'
        ]);
        if ($validator) {

            $insert = new \App\Kelas;
            $insert->id_school = $request->id_school;
            $insert->class_name = $request->class_name;
            $insert->save();
            if ($insert) {
                return Response::json([
                    "message" => "success"
                ],200);
            } else {
                return Response::json([
                    "message" => "failed"
                ],201);
            }
        } else {
            return [
                "message" => $validator->errors()
            ];
        }
    }
}
