<?php


namespace App\Repositories;


use App\Helpers\Helper;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ScheduleRepository
{

    private $schedule;

    /**
     * ScheduleRepository constructor.
     * @param $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }


    public function getStudentScheduleCount($id) {
        $total = $this->schedule->where('requester_id', $id)->count();

        return Response::json([
            "total" => $total
        ], 200);
    }

    public function add(Request $request)
    {
        if ($request->type_schedule == 'realtime') {
            $insert = $this->storeRealtime($request);
        } elseif ($request->type_schedule == 'direct') {
            $insert = $this->storeDirect($request);
        } else {
            $insert = $this->storeDaring($request);
        }

        if ($insert) {
            // Helper::sendNotificationTopic($request->type_schedule);
            return Response::json([
                "message" => 'failed create schedule'
            ], 201);
        }

        return Response::json([
            "message" => 'success create schedule',
            'type_schedule' => $request->type_schedule
        ], 200);
    }

    private function storeRealtime($request)
    {
        $insert = $this->schedule;
        $insert->requester_id = Auth::user()->id;
        $insert->time = $request->time;
        $insert->title = $request->title;
        $insert->desc = $request->desc;
        $insert->type_schedule = 'realtime';
        $insert->save();
        return $insert;
    }


    private function storeDaring($request)
    {
        $insert = $this->schedule;
        $insert->requester_id = Auth::user()->id;
        $insert->title = $request->title;
        $insert->desc = $request->desc;
        $insert->type_schedule = 'daring';
        $insert->save();
        return $insert;
    }

    private function storeDirect($request)
    {
        $insert = $this->schedule;
        $insert->requester_id = Auth::user()->id;
        $insert->title = $request->title;
        $insert->desc = $request->desc;
        $insert->type_schedule = 'direct';
        $insert->time = $request->time;
        $insert->location = $request->location;
        $insert->save();
        return $insert;
    }

    public function put(Request $request)
    {
        if ($request->type_schedule == 'daring') {
            $update = Schedule::where('id', $request->schedule_id)
                ->where('requester_id', Auth::user()->id)
                ->where('exp', 0)
                ->where('status', 0)->update([
                    'title' => $request->title,
                    'desc' => $request->desc
                ]);

            if (!$update) {
                return Response::json([
                    "message" => 'failed to update'
                ], 201);
            }
            return Response::json([
                "message" => 'schedule updated'
            ], 200);
        } else {
            //Direct dan Realtime
            if($this->isExpired($request)) {
                return Response::json(["message" => 'pengajuan telah kadaluarsa.'], 201);
            }

            $update = Schedule::where('id', $request->schedule_id)
                ->where('requester_id', Auth::user()->id)
                ->where('exp', 0)
                ->where('status', 0)->update([
                    'title' => $request->title,
                    'desc' => $request->desc,
                    'time' => $request->time
                ]);

            if (!$update) {
                return Response::json(["message" => 'pengajuan telah diterima oleh guru.'], 201);
            }
            return Response::json(["message" => 'schedule updated'], 200);
        }

        return $request;
    }

    private function isExpired($request)
    {
        if (Carbon::parse($request->original_time)->lte(Carbon::now())) {
            Schedule::where('id', $request->schedule_id)->update([
                'exp'=> 1
            ]);
            return true;
        }
        return false;
    }

}
