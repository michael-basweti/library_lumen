<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'author_id', 'description', 'publisher', 'year_of_publication'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function author(){
        return $this->belongsTo('App\Author');
    }

}

?>
