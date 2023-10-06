<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProductionStoreRequest extends FormRequest
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
            'production_id'     =>  'required|exists:productions,id',
            'received_date'     =>  'required|date_format:d-m-Y',
            'output_id'         =>  'required',
            'weight'            =>  'required',
            'bags'              =>  'required',
            'user_id'           =>  'required|exists:users,id',
        ];
    }


    protected function prepareForValidation(): void
    {

        $bags = $this->bags;
        $bag_weight = $this->bag_weight;

        $weight=[];
        foreach($bags as $key => $info)
        {
            $calculated_weight = $info * $bag_weight[$key];
            $weight[$key] = $calculated_weight;
        }


        $this->merge([
            'user_id' =>  auth()->id(),
            'weight' => $weight
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
