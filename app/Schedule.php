<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {

    protected $fillable = [
        'requester_id','tgl_pengajuan','status','consultant_id','type_schedule','room_id'
    ];

    protected $primaryKey = "id";

    protected $dates = [];

    protected $table = "tbl_schedule";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function request(){
        return $this->hasOne('\App\User','id','requester_id');
    }

    public function consultant(){
        return $this->hasOne('\App\User','id','consultant_id');
    }

}
