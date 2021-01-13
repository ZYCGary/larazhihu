<?php

namespace App\Http\Requests;


class AnswerRequest extends Request
{
    public function rules(): array
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'content' => ['required']
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
