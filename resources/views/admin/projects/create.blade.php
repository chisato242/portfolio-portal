<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">新規作品を追加</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                    @include('admin.projects._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
