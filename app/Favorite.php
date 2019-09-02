<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {

    protected $table = "tbl_fav_artikel";

    protected $primaryKey = "id_favorit";

    protected $fillable = [
        'id','id_artikel','id_user'
    ];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function artikel(){
        return $this->hasOne('\App\Artikel','id','id_artikel')->select(array('id','title','desc','created_at'));
    }

    public function user(){
        return $this->hasOne('\App\User','id','id_user')->select(array('id AS user_id', 'name'));
    }
}
