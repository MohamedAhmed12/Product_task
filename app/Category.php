<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'price', 'units', 'description', 'image'
    ];

    // disabe timestamp
    public $timestamps = false;

    /**
     * Function to relate the categories Table to products table one to many relationship.
     *
     */
    public function products(){
        return $this->hasMany(Product::class);
    }
}
