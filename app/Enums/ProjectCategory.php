<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Web = 'web';
    case Mobile = 'mobile';
    case Tool = 'tool';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'Webアプリ',
            self::Mobile => 'モバイルアプリ',
            self::Tool => 'ツール',
            self::Other => 'その他',
        };
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
