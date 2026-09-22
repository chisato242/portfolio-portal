<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectListResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * 公開済み作品の一覧。category / tag / status のクエリパラメータで絞り込み可能。
     * 例: /api/projects?category=web&tag=Flutter&status=released
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Project::query()
            ->published()
            ->with('tags')
            ->ordered();

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('name', $tag));
        }

        return ProjectListResource::collection($query->paginate(20));
    }

    public function show(Project $project): ProjectResource
    {
        abort_unless($project->is_published, 404);

        $project->load('tags');

        return new ProjectResource($project);
    }

    public function tags()
    {
        return Tag::orderBy('name')->pluck('name');
    }
}
