<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table= "user";

    protected $primaryKey = "id";

    protected $fillable = [
        'username','hasEverChangePassword'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function favorite()
    {
        return $this->hasMany('\App\Favorite');
    }

    public function sekolah() {
        return $this->belongsTo('\App\Sekolah');
    }

    public function diary() {
        return $this->hasMany('\App\Diary');
    }

    public function feeds() {
        return $this->hasMany(Feed::class);
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}
