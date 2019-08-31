<?php

namespace App;

use App\category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'image'
    ];
    
    // disabe timestamp
    public $timestamps = false;
    
    /**
     * Function to relate the products Table to categories table many to one relationship.
     *
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
