<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $fillable = [
        'user_id','schedule_id'
    ];

    protected $dates = [];

    protected $table = "riwayat";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function schedule()
    {
        return $this->hasOne('\App\Schedule', 'id', 'schedule_id');
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
