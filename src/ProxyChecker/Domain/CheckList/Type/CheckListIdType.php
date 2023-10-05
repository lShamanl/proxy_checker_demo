<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\CheckList\Type;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

class CheckListIdType extends BigIntType
{
    public const NAME = 'proxy_checker_check_list_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?string {
        return $value instanceof CheckListId ? $value->__toString() : $value;
    }

    /**
     * @psalm-suppress InvalidNullableReturnType
     * @psalm-suppress NullableReturnStatement
     */
    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?CheckListId {
        return !empty($value) ? new CheckListId((string) $value) : null;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
