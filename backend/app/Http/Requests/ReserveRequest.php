<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReserveRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'exists:App\Models\RoomType,id'],
            'requested_count' => ['required', 'integer', 'gt:0'],
            'date_to_reserve' => ['required', Rule::date()->format('Y-m-d')]
        ];
    }
}
