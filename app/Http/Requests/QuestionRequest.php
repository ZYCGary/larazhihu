<?php

namespace App\Http\Requests;

class QuestionRequest extends Request
{
    public function rules(): array
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'title' => ['required'],
                    'content' => ['required'],
                    'category_id' => ['required', 'exists:categories,id'],
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // UPDATE ROLES
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages(): array
    {
        return [
            // Validation messages
        ];
    }
}
