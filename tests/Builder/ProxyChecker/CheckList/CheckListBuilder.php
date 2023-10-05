<?php

declare(strict_types=1);

namespace App\Tests\Builder\ProxyChecker\CheckList;

use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\Tests\Builder\AbstractBuilder;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CheckListBuilder extends AbstractBuilder
{
    protected CheckListId $id;

    protected DateTimeImmutable $createdAt;

    protected DateTimeImmutable $updatedAt;

    protected ?DateTimeImmutable $endAt;

    protected string $payload;

    protected ?int $allIteration;

    protected ?int $successIteration;

    protected Collection $proxies;

    public function build(): CheckList
    {
        /** @var CheckList $checkList */
        $checkList = self::create($this);

        return $checkList;
    }

    /** @return class-string<CheckList> */
    public static function getEntityClass(): string
    {
        return CheckList::class;
    }

    public static function randomPayload(object $entity): array
    {
        $payload = [];

        $payload['id'] = new CheckListId((string) self::$faker->numberBetween(100000, 9999999));
        $payload['createdAt'] = (new DateTimeImmutable())->sub(new DateInterval('P' . random_int(180, 365) . 'D'));
        $payload['updatedAt'] = (new DateTimeImmutable())->sub(new DateInterval('P' . random_int(1, 179) . 'D'));
        $payload['endAt'] = (new DateTimeImmutable(self::$faker->dateTime->format(DateTimeImmutable::ATOM)));
        $payload['payload'] = self::$faker->text(511);
        $payload['allIteration'] = self::$faker->numberBetween(1, 32767);
        $payload['successIteration'] = self::$faker->numberBetween(1, 32767);
        $payload['proxies'] = new ArrayCollection();

        return $payload;
    }

    public function withId(CheckListId $id): self
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

    public function withEndAt(?DateTimeImmutable $endAt): self
    {
        $clone = clone $this;
        $clone->endAt = $endAt;

        return $clone;
    }

    public function withPayload(string $payload): self
    {
        $clone = clone $this;
        $clone->payload = $payload;

        return $clone;
    }

    public function withAllIteration(?int $allIteration): self
    {
        $clone = clone $this;
        $clone->allIteration = $allIteration;

        return $clone;
    }

    public function withSuccessIteration(?int $successIteration): self
    {
        $clone = clone $this;
        $clone->successIteration = $successIteration;

        return $clone;
    }

    public function withProxies(Collection $proxies): self
    {
        $clone = clone $this;
        $clone->proxies = $proxies;

        return $clone;
    }
}
