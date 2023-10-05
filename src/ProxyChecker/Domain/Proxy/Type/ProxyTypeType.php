<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\Type;

use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type as DoctrineBaseType;
use InvalidArgumentException;

class ProxyTypeType extends DoctrineBaseType
{
    public const NAME = 'proxy_checker_proxy_type';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return self::NAME;
    }

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): mixed {
        if (null === ProxyType::tryFrom((string) $value)) {
            throw new InvalidArgumentException('Invalid enum value');
        }

        return $value;
    }

    /**
     * @psalm-suppress InvalidNullableReturnType
     * @psalm-suppress NullableReturnStatement
     */
    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): mixed {
        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
