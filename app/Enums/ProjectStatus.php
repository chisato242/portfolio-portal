<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Released = 'released';
    case InProgress = 'in_progress';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Released => '公開中',
            self::InProgress => '開発中',
            self::Archived => 'アーカイブ',
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
