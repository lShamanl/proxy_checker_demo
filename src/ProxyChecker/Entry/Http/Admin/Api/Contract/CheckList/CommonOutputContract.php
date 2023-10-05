<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\Contract\CheckList;

use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\Proxy\Proxy;
use DateTimeInterface;

class CommonOutputContract
{
    public string $id;

    public string $createdAt;

    public string $updatedAt;

    public ?string $endAt;

    public string $payload;

    public ?int $allIteration;

    public ?int $successIteration;

    /** @var string[] */
    public ?array $proxyIds;

    public static function create(CheckList $checkList): self
    {
        $contract = new self();
        $contract->id = $checkList->getId()->getValue();
        $contract->createdAt = $checkList->getCreatedAt()->format(DateTimeInterface::ATOM);
        $contract->updatedAt = $checkList->getUpdatedAt()->format(DateTimeInterface::ATOM);
        $contract->endAt = $checkList->getEndAt()?->format(DateTimeInterface::ATOM);
        $contract->payload = $checkList->getPayload();
        $contract->allIteration = $checkList->getAllIteration();
        $contract->successIteration = $checkList->getSuccessIteration();
        $contract->proxyIds = array_map(static fn (Proxy $proxy) => $proxy->getId()->getValue(), $checkList->getProxies());

        return $contract;
    }
}
