<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendContactRequest extends FormRequest
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
        // $this->redirect = url()->previous() . '#contact';
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'string'],
            'phone' => ['string'],
            'message' => ['required']
        ];
    }
}
