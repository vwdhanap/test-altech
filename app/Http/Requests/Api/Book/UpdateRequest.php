<?php

namespace App\Http\Requests\Api\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
             * author_id.
             * 
             * @var integer
             * 
             * @example 1
             */
            'author_id' => 'required|integer|exists:authors,id',
            /**
             * title.
             * 
             * @var string
             * 
             * @example Rich Dad Poor Dad
             */
            'title' => 'required|string|max:255',
            /**
             * description.
             * 
             * @var string
             * 
             * @example Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione voluptas aut perferendis aspernatur iste incidunt voluptatum ab error sit saepe.
             */
            'description' => 'nullable|max:5000',
            /**
             * publish_date.
             * 
             * @var string
             * 
             * @example 2003-05-13
             */
            'publish_date' => 'required|date'
        ];
    }
}
