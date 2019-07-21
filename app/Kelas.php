<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama_kelas'
    ];

    protected $dates = [];

    protected $table = "tbl_kelas";

    public static $rules = [
        // Validation rule
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    // Relationships
}
