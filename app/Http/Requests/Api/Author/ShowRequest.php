<?php

namespace App\Http\Requests\Api\Author;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
             * cache_duration.
             * 
             * @var integer
             * 
             * @example 3600
             */
            'cache_duration' => 'nullable|integer',
        ];
    }
}
