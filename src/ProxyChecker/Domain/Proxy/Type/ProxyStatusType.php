<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\Type;

use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type as DoctrineBaseType;
use InvalidArgumentException;

class ProxyStatusType extends DoctrineBaseType
{
    public const NAME = 'proxy_checker_proxy_status';

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
        if (null === ProxyStatus::tryFrom((string) $value)) {
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
