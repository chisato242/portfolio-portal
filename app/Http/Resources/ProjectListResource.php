<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 一覧表示用の軽量リソース(サムネイル・タグ・ステータスなど概要のみ)
 */
class ProjectListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'thumbnail_url' => $this->thumbnail_url,
            'category' => $this->category->value,
            'category_label' => $this->category->label(),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'tags' => $this->tags->pluck('name'),
            'released_on' => $this->released_on?->toDateString(),
        ];
    }
}
