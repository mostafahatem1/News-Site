<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Category extends Model
{
    use HasFactory,SearchableTrait,Sluggable;
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $searchable = [
        'columns' => [
            'categories.name' => 10,
        ],
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function status()
    {
        return $this->status ? 'active' : 'inactive';
    }
}
