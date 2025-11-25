<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTransactionRequest extends FormRequest
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
            "masters_coa_id" => "sometimes|exists:masters_coa,id",
            "date" => "sometimes",
            "description" => "sometimes|string",
            "amount" => "sometimes|numeric"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "error" => "Validation Error",
            "messages" => $validator->errors(),
        ], 422));
    }
}
