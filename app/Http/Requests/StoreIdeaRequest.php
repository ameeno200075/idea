<?php

namespace App\Http\Requests;

use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIdeaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Idea::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'status' => [
                'required',
                'string',
                'in:'.implode(',', array_column(IdeaStatus::cases(), 'value')),
            ],

            'links' => ['nullable', 'array'],

            'links.*' => ['nullable', 'url', 'max:255'],

            'steps' => ['nullable', 'array'],

            'steps.*.description' => ['nullable', 'string', 'max:255'],
            'steps.*.completed' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5048'],
        ];
    }
}
