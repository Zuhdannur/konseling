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


    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    // Relationships
    public function user() {
        return $this->hasMany('\App\User');
    }

}
