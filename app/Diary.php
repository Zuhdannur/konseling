<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{

    protected $fillable = [
        'id_user','body'
    ];

    protected $dates = [];

    protected $table = "tbl_diary";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user()
    {
        $this->hasOne('\App\User','id_user','id');
    }
}
