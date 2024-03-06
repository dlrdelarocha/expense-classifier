<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryOwnership implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validCategory = Category::where('id', $value)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                ->orWhereNull('user_id');
            })->exists();

       if (!$validCategory) {
           $fail('The category you added cannot be modified or does not belong to you! ');
       }
    }
}
