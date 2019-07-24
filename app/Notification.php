<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $fillable = [
        'title', 'body'
    ];

    protected $dates = [];

    protected $table = "tbl_notification";

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo('\App\User','id_user','id');
    }
}
