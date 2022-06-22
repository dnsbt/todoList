<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoListItemCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'todo_list_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
