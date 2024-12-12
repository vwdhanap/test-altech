<?php

namespace App\Http\Requests\Api\Author;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
             * limit.
             * 
             * @var integer
             * 
             * @example 1
             */
            'limit' => 'nullable|integer',
            /**
             * page.
             * 
             * @var integer
             * 
             * @example 1
             */
            'page' => 'nullable|integer',
            /**
             * order.
             * 
             * @example DESC
             */
            'order' => 'nullable|in:ASC,DESC'
        ];
    }
}
