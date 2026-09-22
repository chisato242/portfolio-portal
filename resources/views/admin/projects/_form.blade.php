@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">タイトル <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">概要(一覧表示用の一言紹介)</label>
        <input type="text" name="summary" value="{{ old('summary', $project->summary ?? '') }}" maxlength="255"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        @error('summary') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">詳細説明</label>
        <textarea name="description" rows="6"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">カテゴリ <span class="text-red-500">*</span></label>
        <select name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            @foreach ($categories as $option)
                <option value="{{ $option['value'] }}"
                    @selected(old('category', $project->category->value ?? '') === $option['value'])>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">ステータス <span class="text-red-500">*</span></label>
        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            @foreach ($statuses as $option)
                <option value="{{ $option['value'] }}"
                    @selected(old('status', $project->status->value ?? '') === $option['value'])>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">制作開始日</label>
        <input type="date" name="started_on"
               value="{{ old('started_on', optional($project->started_on ?? null)->toDateString()) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">公開日</label>
        <input type="date" name="released_on"
               value="{{ old('released_on', optional($project->released_on ?? null)->toDateString()) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">公開URL</label>
        <input type="url" name="url" value="{{ old('url', $project->url ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="https://...">
        @error('url') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">リポジトリURL</label>
        <input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="https://github.com/...">
        @error('repo_url') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">技術タグ(カンマ区切り)</label>
        <input type="text" name="tags"
               value="{{ old('tags', isset($project) ? $project->tags->pluck('name')->join(', ') : '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Laravel, Flutter, Dart">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">サムネイル画像</label>
        <input type="file" name="thumbnail" accept="image/*" class="mt-1 block w-full text-sm">
        @error('thumbnail') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        @if (! empty($project->thumbnail_url))
            <img src="{{ $project->thumbnail_url }}" class="mt-2 w-24 h-24 object-cover rounded">
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">スクリーンショット(複数選択可)</label>
        <input type="file" name="screenshots[]" accept="image/*" multiple class="mt-1 block w-full text-sm">
        @error('screenshots.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

        @if (! empty($project->screenshot_urls))
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($project->screenshot_urls as $index => $shot)
                    <div class="relative">
                        <img src="{{ $shot }}" class="w-20 h-20 object-cover rounded">
                        <label class="absolute -top-2 -right-2 bg-white rounded-full shadow p-1 text-xs cursor-pointer">
                            <input type="checkbox" name="remove_screenshots[]" value="{{ $project->screenshots[$index] }}">
                            削除
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="md:col-span-2 flex items-center gap-2">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" id="is_published" name="is_published" value="1"
               @checked(old('is_published', $project->is_published ?? true))
               class="rounded border-gray-300">
        <label for="is_published" class="text-sm text-gray-700">公開する(オフの場合は下書きとして保存)</label>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">表示順(小さいほど先に表示)</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
        保存
    </button>
    <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">キャンセル</a>
</div>
