<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $table = "tbl_message";

    protected $fillable = [
        'id_room','user_id','body'
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user(){
        $this->hasOne('\App\User','user_id','id');
    }
}
