<?php


namespace App;


use Illuminate\Support\Facades\Auth;

trait RecordFeed
{
    public static function bootRecordsFeed() {
        static::created(function ($model) {
            $model->recordFeed('created');
        });
    }

    public function feeds() {
        return $this->morphMany(Feed::class, 'feedable');
    }

    public function recordFeed($event) {
        $this->feeds()->create([
            'user_id' => Auth::user()->id,
            'type' => $event . '_' . strtolower(class_basename($this))
        ]);
    }

}
