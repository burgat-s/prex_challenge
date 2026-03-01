<?php

namespace App\Http\Requests;

class StoreFavoriteRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gif_id'  => ['required', 'string'], 
            'alias'   => ['required', 'string'],
            'user_id' => ['required', 'integer'],
        ];
    }
}