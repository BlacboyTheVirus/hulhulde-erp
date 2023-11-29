<?php

namespace App\Http\Requests;

use App\Enums\PaymentStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreInvoiceStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'invoice_id'        =>  'required|exists:invoices,id',
            'released_date'     =>  'required|date_format:d-m-Y',
            'product_id'        =>  'required|exists:products,id',
            'released_by'       =>  'required|string',
            'quantity'          =>  'required',
            'bag_weight'        =>  'required',
            'user_id'           =>  'required|exists:users,id',
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id'           =>  auth()->id(),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $error_text = "";
        foreach ($errors as $err){
            $error_text .= $err . " ";
        }

        $response = response()->json([
            'success' => false,
            'message' => $error_text,
        ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
