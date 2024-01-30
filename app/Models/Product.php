<?php

namespace App\Models;

use App\Helper\ImageHelper;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'shot_desc',
        'desc',
        'remark',
        'reqular_price',
        'sales_price',
        'featured_image',
        'gallery_images',
        'weight',
        'length',
        'width',
        'height',
        'stock',
        'brand_id',
        'is_active',
        'is_featured',
    ];

    //hide pivot
    protected $hidden = [
        'pivot',
    ];

    protected $cast = [
        'gallery_images' => 'array',
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

    //featued image

    public function setFeaturedImageAttribute($value)
    {
        if (!$value) {
            $value = 'image/placeholder.jpg';
        }else{
            $value = ImageHelper::imageUpload($value, $this->attributes['name']);
        }
        $this->attributes['featured_image'] =  $value;
    }

    public function getFeaturedImageAttribute($value)
    {
        return asset('storage/' . $value);
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute', 'product_id', 'attribute_id')
            ->withPivot('attribute_value');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function remarks()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }
}
