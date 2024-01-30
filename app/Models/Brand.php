<?php

namespace App\Models;

use App\Helper\ImageHelper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'logo',
        'slug',
    ];

    public static function findByIdOrSlug($identifier)
    {
        return static::where('id', $identifier)->orWhere('slug', $identifier);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getLogoAttribute($value)
    {
        // Add any additional logic here
        return asset('storage/'.$value);
    }
    
    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }


}
