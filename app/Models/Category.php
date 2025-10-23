<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasSlug;
    use HasFactory;

    protected $fillable = [
        'title',"slug",'description', 'is_active'
    ];

public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = self::generateUniqueSlug($category->title, 'categories' );
        });

        static::updating(function ($category) {
            $category->slug = self::generateUniqueSlug($category->title, 'categories', 'slug', $category->id);
        });
    }
}
