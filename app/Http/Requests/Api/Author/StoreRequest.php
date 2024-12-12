<?php

namespace App\Http\Requests\Api\Author;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            /**
             * name.
             * 
             * @var string
             * 
             * @example Napoleon Hill
             */
            'name' => 'required|string|max:255',
            /**
             * bio.
             * 
             * @var string
             * 
             * @example Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione voluptas aut perferendis aspernatur iste incidunt voluptatum ab error sit saepe.
             */
            'bio' => 'nullable|max:5000',
            /**
             * birth_date.
             * 
             * @var string
             * 
             * @example 1994-05-03
             */
            'birth_date' => 'required|date'
        ];
    }
}
