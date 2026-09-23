<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'thumbnail_path',
        'screenshots',
        'url',
        'repo_url',
        'category',
        'status',
        'started_on',
        'released_on',
        'is_published',
        'sort_order',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'category' => ProjectCategory::class,
        'status' => ProjectStatus::class,
        'started_on' => 'date',
        'released_on' => 'date',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-" . ++$i;
        }

        return $slug;
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('released_on')->orderByDesc('id');
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => $this->thumbnail_path
            ? Storage::disk('s3')->url($this->thumbnail_path)
            : null);
    }

    protected function screenshotUrls(): Attribute
    {
        return Attribute::get(function () {
            return collect($this->screenshots ?? [])
                ->map(fn (string $path) => Storage::disk('s3')->url($path))
                ->values()
                ->all();
        });
    }
}
