<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MastersController extends Controller
{

    public function getListSchool()
    {
        $data = \App\School::all();
        return [
            "message" => "success",
            "result" => $data
        ];
    }

    public function getListClass($id)
    {
        $data = \App\Kelas::where('id_school', $id)->get();
        return [
            "message" => 'success',
            "result" => $data
        ];
    }

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
                return [
                    "message" => "success"
                ];
            } else {
                return [
                    "message" => "failed"
                ];
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
        if($delete){
            return [
                "message" => 'success'
            ];
        } else {
            return [
                "message" => 'failed'
            ];
        }
    }

    public function storeClass(Request $request)
    {
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
                return [
                    "message" => "success"
                ];
            } else {
                return [
                    "message" => "failed"
                ];
            }
        } else {
            return [
                "message" => $validator->errors()
            ];
        }
    }


}
