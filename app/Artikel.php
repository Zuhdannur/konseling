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

    protected $appends = 'liked_by_auth_user';

    public function getLikedByAuthUserAttribute()
    {
        $userId = Auth::user()->id;
        
        $like = $this->likes->first(function ($key, $value) use ($userId) {
            return $value->user_id === $userId;
        });
        
        if ($like) {
            return true;
        }
        
        return false;
    }
    

    // Relationships
}
