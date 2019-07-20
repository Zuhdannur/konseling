<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
       'id', 'school_name','address'
    ];

    protected $dates = [];

    protected $table = "tbl_school";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    // public function kelas(){
    //     return $this->hasMany('\App\Class','id_school','id');
    // }
}
