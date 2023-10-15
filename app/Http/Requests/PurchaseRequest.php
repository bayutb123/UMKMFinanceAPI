<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'transaction_date' => 'required|date',
            'user_id' => 'required|numeric',
            'vendor_id' => 'required|numeric',
            'description' => 'required|string',
            'account_id' => 'required|numeric',
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'type' => 'required|integer',
        ];
    }
}
