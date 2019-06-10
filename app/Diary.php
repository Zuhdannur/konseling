<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'id_user','body','title','tgl','created_at','updated_at'
    ];

    protected $dates = [];

    protected $table = "tbl_diary";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user()
    {
       return $this->belongsTo('\App\User','id_user','id');
    }
}
