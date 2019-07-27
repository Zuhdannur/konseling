<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $fillable = [
        'id','user_id','schedule_id'
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function schedule()
    {
        return $this->hasOne('\App\Schedule', 'id', 'schedule_id');
    }
}
