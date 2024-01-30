<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'logo',
        'slug',
        'parent_id',
        'is_default'
    ];

    protected $hidden = [
        'pivot',
    ];

    public static function findByIdOrSlug($identifier)
    {
        return static::where('id', $identifier)->orWhere('slug', $identifier);
    }

    public function setSlugAttribute($value)
    {
        if (!$value) {
            $value = $this->attributes['name'];
        }
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getLogoAttribute($value)
    {
        if (!$value) {
            $value = 'image/placeholder.jpg';
        }
        return asset('storage/' . $value);
    }

    public function setLogoAttribute($value){
        if (!$value) {
            $value = 'image/placeholder.jpg';
        }
        $this->attributes['logo'] = $value;
    }

    //General error: 1364 Field 'slug' doesn't have a default value


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
