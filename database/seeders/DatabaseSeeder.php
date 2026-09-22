<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者ユーザー(ログイン用)。メール・パスワードは環境変数か直接書き換えてください。
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // 必ず初回ログイン後に変更してください
            ]
        );

        $tags = collect(['Laravel', 'Flutter', 'Dart', 'PHP', 'TypeScript', 'Vue.js'])
            ->map(fn (string $name) => Tag::findOrCreateByName($name));

        Project::firstOrCreate(
            ['slug' => 'sample-project'],
            [
                'title' => 'サンプルプロジェクト',
                'summary' => 'ポータルサイトの動作確認用サンプル作品です。',
                'description' => "ここに作品の詳細説明を書きます。\n\nMarkdownではなくプレーンテキスト/改行想定です。",

                'url' => 'https://example.com',
                'repo_url' => 'https://github.com/example/example',
                'category' => 'web',
                'status' => 'released',
                'started_on' => now()->subMonths(3),
                'released_on' => now()->subMonth(),
                'is_published' => true,
                'sort_order' => 0,
            ]
        )->tags()->sync($tags->take(2)->pluck('id'));
    }
}
