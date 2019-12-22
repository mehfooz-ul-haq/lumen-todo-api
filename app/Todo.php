<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['user_id','category_id','name','description','date_time','status_id'];

    protected $hidden = [
        'user_id', 'created_at', 'updated_at'
    ];
        
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function filter($request) {
        
    }


}