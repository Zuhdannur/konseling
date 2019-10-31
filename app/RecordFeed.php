<?php


namespace App;


use Illuminate\Support\Facades\Auth;

trait RecordFeed
{
    public static function bootRecordsFeed() {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordFeed($event);
            });
        }
    }

    public function feeds() {
        return $this->morphMany(Feed::class, 'feedable');
    }

    public function recordFeed($event) {
        Feed::create([
            'user_id' => Auth::user()->id,
            'type' => $event . '_' . strtolower(class_basename($this))
        ]);
    }

    protected function getActivityName($model, $action)
    {
        $name = strtolower((new ReflectionClass($model))->getShortName());

        return "{$action}_{$name}";
    }

    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'deleted', 'updated'
        ];
    }

}
