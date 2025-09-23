<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\TripayMethods;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
        $this->merge([
            'payment_method' => strtoupper((string) $this->input('payment_method')),
        ]);

        return [
            'product_id' => ['required','exists:products,id'],
            'buyer_email' => ['required','email'],
            'buyer_phone' => ['required','string','max:32'],
            'payment_method' => ['required','string','max:50'],
            'payment_method' => [
                'required','string','max:50',
                Rule::in(TripayMethods::codes()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.in' => 'Metode pembayaran tidak didukung.',
        ];
    }
}
