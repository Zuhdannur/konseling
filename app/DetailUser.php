<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $fillable = [
        'id_user','gender','address','phone_number','id_sekolah','id_kelas'
    ];

    protected $dates = [];

    protected $table = "tbl_detail_user";

    public static $rules = [
        // Validation rules
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    // Relationships
    public function kelas()
    {
        return $this->hasOne('\App\Kelas', 'id', 'id_kelas');
    }

    public function sekolah()
    {
        return $this->hasOne('\App\School', 'id', 'id_sekolah');
    }
}
