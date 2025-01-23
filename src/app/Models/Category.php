<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category');
    }

    public static function getParentCategories()
    {
        return self::orderBy('name')->get();
    }
}