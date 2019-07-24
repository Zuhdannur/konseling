<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class NotifikasisController extends Controller {

    public function all(Request $filters) {
        $app = new \App\Notification;
        $app = $app->where('id_user', Auth::user()->id)->get();

        $limit = $filters->limit;

        if (empty($filters->page)) $skip = 0;
        else $skip = $limit * $filters->page;

        $data = $schedule
            ->skip($skip)
            ->take($limit);

        return Response::json($data, 200);
    }

}