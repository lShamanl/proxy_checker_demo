<?php

declare(strict_types=1);

namespace App\Tests\Builder\ProxyChecker\Proxy;

use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use App\ProxyChecker\Domain\Proxy\Proxy;
use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use App\Tests\Builder\AbstractBuilder;
use DateInterval;
use DateTimeImmutable;

class ProxyBuilder extends AbstractBuilder
{
    protected ProxyId $id;

    protected DateTimeImmutable $createdAt;

    protected DateTimeImmutable $updatedAt;

    protected string $ipProxy;

    protected string $ipReal;

    protected int $port;

    protected ProxyType $proxyType;

    protected ProxyStatus $proxyStatus;

    protected ?string $country;

    protected ?string $region;

    protected ?string $city;

    protected ?int $timeout;

    protected CheckList $checkList;

    public function build(): Proxy
    {
        /** @var Proxy $proxy */
        $proxy = self::create($this);

        return $proxy;
    }

    /** @return class-string<Proxy> */
    public static function getEntityClass(): string
    {
        return Proxy::class;
    }

    public static function randomPayload(object $entity): array
    {
        $payload = [];

        $payload['id'] = new ProxyId((string) self::$faker->numberBetween(100000, 9999999));
        $payload['createdAt'] = (new DateTimeImmutable())->sub(new DateInterval('P' . random_int(180, 365) . 'D'));
        $payload['updatedAt'] = (new DateTimeImmutable())->sub(new DateInterval('P' . random_int(1, 179) . 'D'));
        $payload['ipProxy'] = self::$faker->text(255);
        $payload['ipReal'] = self::$faker->text(255);
        $payload['port'] = self::$faker->numberBetween(1, 32767);
        $payload['proxyType'] = self::$faker->randomElement(ProxyType::cases());
        $payload['proxyStatus'] = self::$faker->randomElement(ProxyStatus::cases());
        $payload['country'] = self::$faker->text(255);
        $payload['region'] = self::$faker->text(255);
        $payload['city'] = self::$faker->text(255);
        $payload['timeout'] = self::$faker->numberBetween(1, 32767);

        return $payload;
    }

    public function withId(ProxyId $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function withCreatedAt(DateTimeImmutable $createdAt): self
    {
        $clone = clone $this;
        $clone->createdAt = $createdAt;

        return $clone;
    }

    public function withUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $clone = clone $this;
        $clone->updatedAt = $updatedAt;

        return $clone;
    }

    public function withIpProxy(string $ipProxy): self
    {
        $clone = clone $this;
        $clone->ipProxy = $ipProxy;

        return $clone;
    }

    public function withIpReal(string $ipReal): self
    {
        $clone = clone $this;
        $clone->ipReal = $ipReal;

        return $clone;
    }

    public function withPort(int $port): self
    {
        $clone = clone $this;
        $clone->port = $port;

        return $clone;
    }

    public function withProxyType(ProxyType $proxyType): self
    {
        $clone = clone $this;
        $clone->proxyType = $proxyType;

        return $clone;
    }

    public function withProxyStatus(ProxyStatus $proxyStatus): self
    {
        $clone = clone $this;
        $clone->proxyStatus = $proxyStatus;

        return $clone;
    }

    public function withCountry(?string $country): self
    {
        $clone = clone $this;
        $clone->country = $country;

        return $clone;
    }

    public function withRegion(?string $region): self
    {
        $clone = clone $this;
        $clone->region = $region;

        return $clone;
    }

    public function withCity(?string $city): self
    {
        $clone = clone $this;
        $clone->city = $city;

        return $clone;
    }

    public function withTimeout(?int $timeout): self
    {
        $clone = clone $this;
        $clone->timeout = $timeout;

        return $clone;
    }

    public function withCheckList(CheckList $checkList): self
    {
        $clone = clone $this;
        $clone->checkList = $checkList;

        return $clone;
    }
}
