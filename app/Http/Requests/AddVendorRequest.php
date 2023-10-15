<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddVendorRequest extends FormRequest
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
            'owner_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'owner' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:100',
        ];
    }
}
