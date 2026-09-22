<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 詳細表示用のフルリソース
 */
class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnail_url,
            'screenshot_urls' => $this->screenshot_urls,
            'url' => $this->url,
            'repo_url' => $this->repo_url,
            'category' => $this->category->value,
            'category_label' => $this->category->label(),
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'started_on' => $this->started_on?->toDateString(),
            'released_on' => $this->released_on?->toDateString(),
            'tags' => $this->tags->pluck('name'),
        ];
    }
}
