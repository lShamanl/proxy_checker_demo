<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\Type;

use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

class ProxyIdType extends BigIntType
{
    public const NAME = 'proxy_checker_proxy_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?string {
        return $value instanceof ProxyId ? $value->__toString() : $value;
    }

    /**
     * @psalm-suppress InvalidNullableReturnType
     * @psalm-suppress NullableReturnStatement
     */
    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?ProxyId {
        return !empty($value) ? new ProxyId((string) $value) : null;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
