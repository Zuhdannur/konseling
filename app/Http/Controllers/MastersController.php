<?php namespace App\Http\Controllers;

use http\Env\Request;

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

    public function show($id)
    {
        $data = \App\Kelas::all();
        return [
            "message" => 'success',
            "result" => $data
        ];
    }

    public function store(Request $request)
    {

    }

    public function create(Request $request)
    {

    }


}
