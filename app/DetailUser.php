<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model {

    protected $fillable = [
        'id_user','gender','address','phone_number','class','school'
    ];

    protected $dates = [];

    protected $table = "tbl_detail_user";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
