<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\Enum;

enum ProxyType: string
{
    case Http = 'http';
    case Socks = 'socks';
    case Other = 'other';

    public function isEqual(self $enum): bool
    {
        return $this->value === $enum->value;
    }

    /** @param self[] $enums */
    public function isEqualAtLeastOne(array $enums): bool
    {
        foreach ($enums as $enum) {
            if ($this->value === $enum->value) {
                return true;
            }
        }

        return false;
    }

    public static function states(): array
    {
        return array_column(self::cases(), 'value');
    }
}
