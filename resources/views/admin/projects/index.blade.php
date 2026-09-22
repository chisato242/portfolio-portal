<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">作品一覧</h2>
            <a href="{{ route('admin.projects.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                + 新規作品を追加
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">サムネイル</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">タイトル</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">カテゴリ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">公開</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-4 py-3">
                                    @if ($project->thumbnail_url)
                                        <img src="{{ $project->thumbnail_url }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">No Image</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $project->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $project->tags->pluck('name')->join(', ') }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $project->category->label() }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $project->status->label() }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($project->is_published)
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">公開中</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600">下書き</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-sm text-indigo-600 hover:underline">編集</a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline"
                                          onsubmit="return confirm('「{{ $project->title }}」を削除します。よろしいですか?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    まだ作品が登録されていません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
