<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category');
    }

    public static function getParentCategories()
    {
        return self::whereNull('parent_id')->get();
    }
}
