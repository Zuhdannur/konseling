<?php


namespace App\Http\Controllers\Master;
use App\Feed;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class FeedController
{
    private $feed;
    private $user;

    /**
     * FeedController constructor.
     * @param $feed
     * @param $user
     */
    public function __construct(Feed $feed, User $user)
    {
        $this->feed = $feed;
        $this->user = $user;
    }

    /**
     * FeedController constructor.
     * @param $feed
     */


    public function all() {
        $data = $this->feed->where('user_id', Auth::user()->id)->get();
        return \response()->json($data, 200);
    }


}
