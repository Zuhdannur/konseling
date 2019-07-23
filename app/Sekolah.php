<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = [
       'id', 'nama_sekolah','alamat'
    ];

    protected $dates = [];

    protected $table = "tbl_sekolah";

    protected $hidden = [
        'created_at','updated_at'
    ];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    // public function kelas(){
    //     return $this->hasMany('\App\Class','id_Sekolah','id');
    // }
}
