<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function admin()
    {
        return $this->hasMany(Admin::class, 'role_id', 'id');
    }



}
