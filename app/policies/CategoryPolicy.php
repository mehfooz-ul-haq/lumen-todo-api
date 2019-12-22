<?php

namespace App\Policies;

use App\User;
use App\Category;

class CategoryPolicy
{
    public function update(User $user, Category $category) {
        return $category->user_id === $user->id;
    }

}