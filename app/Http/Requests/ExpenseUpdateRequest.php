<?php

namespace App\Http\Requests;

use App\Rules\CategoryOwnership;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['filled', 'string', 'max:255'],
            'amount' => ['filled', 'numeric', 'min:0'],
            'spent_at' => ['filled', 'date', 'before_or_equal:today'],
            'category_id' => ['filled', 'exists:categories,id', new CategoryOwnership],
        ];
    }
}
