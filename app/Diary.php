<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $fillable = [
        'user_id','body','title','tgl'
    ];

    public $timestamps = true;

    protected $dates = [];

    protected $table = "diary";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user()
    {
       return $this->belongsTo('\App\User');
    }
}
