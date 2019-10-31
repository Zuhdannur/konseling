<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sekolah extends Model
{
    protected $fillable = [
       'id', 'nama_sekolah','alamat'
    ];

    protected $dates = [];

    protected $table = "sekolah";

    protected $hidden = [
        'created_at','updated_at'
    ];

    public static $rules = [
        // Validation rules
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($sekolah) {
            $sekolah->recordFeed('created');
        });
    }

    protected function recordFeed($event) {
        $this->feeds()->create([
            'user_id' => Auth::user()->id,
            'type' => $event.'_'.strtolower(class_basename($this))
        ]);
    }

    public function feeds() {
        return $this->morphMany(Feed::class, 'feedable');
    }


    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    // Relationships
    public function user() {
        return $this->hasMany('\App\User');
    }

    public function firstAdmin() {
        return $this->hasOne('\App\User')->where('role','admin');
    }

}
