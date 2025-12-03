<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Contact extends Model
{
    use HasFactory, HasFactory,SearchableTrait;
    protected $fillable = [
        'name',
        'email',
        'title',
        'body',
        'phone',
        'ip_address',
    ];
    protected $searchable = [
        'columns' => [
            'contacts.name' => 10,
            'contacts.email' => 10,
            'contacts.title' => 10,
            'contacts.body' => 10,
        ],
    ];
}
