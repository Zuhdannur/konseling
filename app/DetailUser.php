<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $fillable = [
        'id_user','jenkel','alamat','nomor_hp','id_sekolah','kelas','kota','tanggal_lahir','kota_lahir'
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

    public function sekolah()
    {
        return $this->hasOne('\App\Sekolah', 'id', 'id_sekolah');
    }

    public function user()
    {
        return $this->belongsTo('\App\User', 'id', 'id_user');
    }

}
