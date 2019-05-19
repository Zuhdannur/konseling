<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $table = "tbl_message";
    public $primaryKey = "id_message";
    protected $fillable = [
        'id_message','sender_id','reciever_id','body'
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user(){
        $this->hasOne('\App\User','id','sender_id');
    }

    public function receiver(){
        $this->hasOne('\App\User','id','receiver_id');
    }
}
