<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CatatanKonseling extends Model
{
    protected $fillable = [
        'user_id','schedule_id'
    ];

    protected $dates = [];

    protected $table = "tbl_catatan_konseling";

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
