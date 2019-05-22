<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {

    protected $fillable = [
        'id_school','class_name'
    ];

    protected $dates = [];

    protected $table = "tbl_class";

    public static $rules = [
        // Validation rules
    ];

    // Relationships

}
