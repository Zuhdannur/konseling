<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SchedulesController extends Controller
{
    public function send(Request $request)
    {
        if (Auth::user()->role == "siswa") {

            $insert = new \App\Schedule;
            $insert->requester_id = Auth::user()->id;
            $insert->tgl_pengajuan = $request->date;
            $insert->consultant_id = $this->getConsultan()->id;
            $insert->save();
            return [
                "message" => 'success create schedule'
            ];
        } else {

            $update = \App\Schedule::where('id',$request->schedule_id)->update([
                'status' => 1
            ]);
            if($update){
                return [
                    "message" => "accept"
                ];
            } else {
                return [
                    "message" => "failed accept"
                ];
            }
        }
    }

    public function getConsultan()
    {
        $data = \App\User::where('role','guru')->get();
        return $data[rand(0,count($data) - 1)];
    }

}
