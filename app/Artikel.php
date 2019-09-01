<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'id','img','title','desc'
    ];

    protected $table = "tbl_artikel";

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
}
