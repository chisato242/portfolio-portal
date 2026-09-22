<?php

namespace App\Http\Requests;

use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ルート側でauthミドルウェア必須
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:5120'], // 5MB
            'screenshots' => ['nullable', 'array'],
            'screenshots.*' => ['image', 'max:5120'],
            'url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'category' => ['required', Rule::in(array_column(ProjectCategory::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(ProjectStatus::cases(), 'value'))],
            'started_on' => ['nullable', 'date'],
            'released_on' => ['nullable', 'date'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
            'tags' => ['nullable', 'string'], // カンマ区切りで受け取り、コントローラでパース
        ];
    }
}
