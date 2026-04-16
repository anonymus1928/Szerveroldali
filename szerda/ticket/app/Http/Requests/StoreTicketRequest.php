<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Ticket::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:100',
                'ends_with:please',
                // Egyedi rule
                // function (string $attribute, mixed $value, Closure $fail) { $fail('valami gond van'); }
            ],
            'priority' => ['required', 'integer', 'between:0,3'],
            'text' => ['required', 'string'],
            'file' => ['nullable', 'file']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute mező kitöltése kötelező!',
            'string' => ':attribute mező csak szöveget tartalmazhat!',
            'max' => ':attribute mező maximálisan :max karakteres lehet!',
            'ends_with' => 'Bunkó vagy!',
            'integer' => ':attribute mező csak egész számot tartalmazhat!',
            'between' => ':attribute mező tartalma :min és :max közötti egész szám lehet csak!',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'A cím',
            'priority' => 'A fontossági szint',
            'text' => 'A hibaleírás',
        ];
    }
}
