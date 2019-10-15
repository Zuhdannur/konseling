<?php


namespace App\Repositories;


use App\Schedule;
use Illuminate\Http\Request;
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

}
