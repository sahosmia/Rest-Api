<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasSlug;
    use HasFactory;

    protected $fillable = [
        'title', 'category_id', 'description', 'photo', 'user_id', 'is_active'
    ];

  public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = self::generateUniqueSlug($blog->title, 'blogs');
        });

        static::updating(function ($blog) {
            $blog->slug = self::generateUniqueSlug($blog->title, 'blogs', 'slug', $blog->id);
        });
    }
}
