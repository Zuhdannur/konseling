<?php namespace App\Http\Controllers;

use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class SchedulesController extends Controller
{
    public function send(Request $request)
    {
        if (Auth::user()->role == "siswa") {
            if ($request->type_schedule == "online") {
                //Store Online With Schedule
                $insert = $this->storeOnlineRequest($request);
            } elseif ($request->type_schedule == "direct") {
                // Go to Direct
                $insert = $this->storeDirect($request);
            } else {
                //Store To Daring
                $insert = $this->storeDaring($request);
            }
            if ($insert) {
                $pusher = new Pusher(
                    'e06a6bacb2b9f8503317',
                    '865963b7338a3b21359a',
                    '786060',
                    [
                        'cluster' => 'ap1',
                        'useTLS' => true
                    ]
                );

                $data['message'] = "Success create schedule";
//                $data['consultant_id'] = $consultant;

                $pusher->trigger('notif-schedule', 'my-event', $data);
                return \Illuminate\Support\Facades\Response::json([
                    "message" => 'success create schedule'
                ], 200);
            } else {
                return Response::json([
                    "message" => 'failed create schedule'
                ], 201);
            }

        } else {

            $update = \App\Schedule::where('id', $request->schedule_id)->update([
                'status' => 1,
                'tgl_pengajuan' => $request->date,
                'consultant_id' => Auth::user()->id
            ]);

            $checkType = \App\Schedule::where('id', $request->schedule_id)->first();

            if ($checkType->type_schedule == "online") {
                $createRoom = \App\Schedule::where('id', $request->schedule_id)->update([
                    'room_id' => base64_encode(str_random(5))
                ]);
            }
            if ($update) {
                $pusher = new Pusher(
                    'e06a6bacb2b9f8503317',
                    '865963b7338a3b21359a',
                    '786060',
                    [
                        'cluster' => 'ap1',
                        'useTLS' => true
                    ]
                );

                $data['message'] = "confirmation schedule";
                $data['requester_id'] = \App\Schedule::where('id', $request->schedule_id)->first()->requester_id;

                $pusher->trigger('notif-schedule', 'my-event', $data);

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
        $data = \App\User::where('role', 'guru')->get();
        return $data[rand(0, count($data) - 1)];
    }

    public function viewMySchedule($id = '')
    {
        if (Auth::user()->role == "siswa" && $id == '') {
            $data['result'] = \App\Schedule::where('requester_id', Auth::user()->id)->first();
            $data['result']['user'] = \App\User::where('id', $data['result']->consultant_id)->first();

            return [
                "message" => "success",
                "result" => $data
            ];

        } else {
            $user = \App\User::where('id', Auth::user()->id)->with('detail')->first();
            $schedule = \App\Schedule::where('type_schedule',$id)->with('request')->with('consultant')->get();
            $data = [];
            foreach ($schedule as $key => $value) {
                if ($this->getSchoolName($value->requester_id)['detail']['school']== $user['detail']['school']) {
                    $data[$key] = $value;
                }
            }
            return Response::json($data, 201);
        }
    }

    private function getSchoolName($id)
    {
        $data = \App\User::where('id', $id)->with('detail')->first();
        return $data;
    }

    private function storeOnlineRequest($request)
    {
        $insert = new \App\Schedule;
        $insert->requester_id = Auth::user()->id;
        $insert->tgl_pengajuan = $request->date;
//            $consultant = $this->getConsultan()->id;
        $insert->title = $request->title;
        $insert->desc = $request->desc;
        $insert->type_schedule = $request->type_schedule;
//            $insert->consultant_id = $consultant;
        $insert->save();
        return $insert;
    }

    private function storeDaring($request)
    {
        $insert                 = new \App\Schedule;
        $insert->requester_id   = Auth::user()->id;
        $insert->title          = $request->title;
        $insert->desc           = $request->desc;
        $insert->type_schedule  = $request->type_schedule;
        $insert->save();
        return $insert;
    }

    private function storeDirect($request)
    {
        $insert                 = new \App\Schedule;
        $insert->requester_id   = Auth::user()->id;
        $insert->title          = $request->title;
        $insert->desc           = $request->desc;
        $insert->type_schedule  = $request->type_schedule;
        $insert->time           = $request->time;
        $insert->location       = $request->location;
        $insert->save();
        return $insert;
    }

}
