<?php

namespace App\Http\Requests;

class SearchGifRequest extends BaseApiRequest 
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query'  => ['required', 'string'],
            'limit'  => ['nullable', 'integer', 'min:1', 'max:50'],
            'offset' => ['nullable', 'integer', 'min:0'],
        ];
    }
}