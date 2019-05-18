<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {

    protected $fillable = [
        'id_room'
    ];

    protected $table = "tbl_room";

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function message(){
        $this->hasMany('\App\Message','id_room','id_room');
    }
}
