<?php

namespace App\Http\Requests;

class UpdateProjectRequest extends StoreProjectRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        // 更新時は既存スクリーンショットの削除指定を受け付ける
        $rules['remove_screenshots'] = ['nullable', 'array'];
        $rules['remove_screenshots.*'] = ['string'];

        return $rules;
    }
}
