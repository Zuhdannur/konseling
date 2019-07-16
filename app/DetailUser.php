<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model {

    protected $fillable = [
        'id_user','gender','address','phone_number','kelas','school','id_kelas'
    ];

    protected $dates = [];

    protected $table = "tbl_detail_user";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function kelas(){
        return $this->hasOne('\App\Kelas','id_kelas','id_kelas');
    }
}
