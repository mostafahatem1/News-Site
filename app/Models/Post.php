<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Post extends Model
{
    use HasFactory,Sluggable,SearchableTrait;
    protected $guarded = [];
    protected $searchable = [
        'columns' => [
            'posts.title' => 10,
            'users.name' => 2,
            'categories.name' => 2,
        ],
        'joins' => [
            'users' => ['users.id', 'posts.user_id'],
            'categories' => ['categories.id', 'posts.category_id'],
        ],
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function images()
    {

     return $this->hasMany(Image::class);
    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeActiveUser($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('status', 1);
        });
    }
      public function scopeActiveCategory($query)
    {
        return $query->whereHas('category', function ($q) {
            $q->where('status', 1);
        });
    }
    public function status()
    {
        return $this->attributes['status'] === 1 ? 'Active' : 'Inactive';
    }

    public function comment_able()
    {
        return $this->comment_able ? 'Active' : 'Inactive';
    }


}
