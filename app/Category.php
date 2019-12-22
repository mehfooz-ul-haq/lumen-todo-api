<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id'];
    
    protected $hidden = [
       'user_id', 'created_at', 'updated_at'
    ];

    public function todos() {
        return $this->hasMany(Todo::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }
}