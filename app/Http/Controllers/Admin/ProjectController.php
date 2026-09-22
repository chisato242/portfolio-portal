<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectCategory;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->with('tags')
            ->ordered()
            ->paginate(20);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'categories' => ProjectCategory::options(),
            'statuses' => ProjectStatus::options(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['thumbnail', 'screenshots', 'tags']);
        $data['is_published'] = $request->boolean('is_published');
        $data['slug'] = Project::generateUniqueSlug($request->string('title'));

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('screenshots')) {
            $data['screenshots'] = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('projects/screenshots', 'public'))
                ->all();
        }

        $project = Project::create($data);

        $this->syncTags($project, $request->string('tags', ''));

        return redirect()
            ->route('admin.projects.index')
            ->with('status', "「{$project->title}」を作成しました。");
    }

    public function edit(Project $project): View
    {
        $project->load('tags');

        return view('admin.projects.edit', [
            'project' => $project,
            'categories' => ProjectCategory::options(),
            'statuses' => ProjectStatus::options(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $request->safe()->except(['thumbnail', 'screenshots', 'tags', 'remove_screenshots']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail_path) {
                Storage::disk('public')->delete($project->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $screenshots = collect($project->screenshots ?? []);

        if ($remove = $request->input('remove_screenshots')) {
            Storage::disk('public')->delete($remove);
            $screenshots = $screenshots->diff($remove)->values();
        }

        if ($request->hasFile('screenshots')) {
            $newPaths = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('projects/screenshots', 'public'));
            $screenshots = $screenshots->merge($newPaths)->values();
        }

        $data['screenshots'] = $screenshots->all();

        $project->update($data);

        $this->syncTags($project, $request->string('tags', ''));

        return redirect()
            ->route('admin.projects.index')
            ->with('status', "「{$project->title}」を更新しました。");
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->thumbnail_path) {
            Storage::disk('public')->delete($project->thumbnail_path);
        }
        Storage::disk('public')->delete($project->screenshots ?? []);

        $title = $project->title;
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('status', "「{$title}」を削除しました。");
    }

    /**
     * カンマ区切りのタグ文字列を作品に同期する。
     */
    private function syncTags(Project $project, string $tagsInput): void
    {
        $tagIds = collect(explode(',', $tagsInput))
            ->map(fn (string $name) => trim($name))
            ->filter()
            ->unique()
            ->map(fn (string $name) => Tag::findOrCreateByName($name)->id);

        $project->tags()->sync($tagIds);
    }
}
