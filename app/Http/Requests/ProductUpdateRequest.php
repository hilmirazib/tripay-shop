<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return $this->user()?->can('product.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => ['required','string','max:64', Rule::unique('products','sku')->ignore($this->route('product'))],
            'name' => ['required','string','max:255'],
            'price' => ['required','integer','min:0'],
            'reference' => ['nullable','string','max:255'],
        ];
    }
}
