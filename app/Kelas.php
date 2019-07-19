<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {

    protected $fillable = [
        'id_kelas','class_name'
    ];

    protected $dates = [];

    protected $table = "tbl_kelas";

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
