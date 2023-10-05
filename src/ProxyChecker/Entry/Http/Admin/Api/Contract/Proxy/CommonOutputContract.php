<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\Contract\Proxy;

use App\ProxyChecker\Domain\Proxy\Proxy;
use DateTimeInterface;

class CommonOutputContract
{
    public string $id;

    public string $createdAt;

    public string $updatedAt;

    public string $ipProxy;

    public string $ipReal;

    public int $port;

    public string $proxyType;

    public string $proxyStatus;

    public ?string $country;

    public ?string $region;

    public ?string $city;

    public ?int $timeout;

    public string $checkListId;

    public static function create(Proxy $proxy): self
    {
        $contract = new self();
        $contract->id = $proxy->getId()->getValue();
        $contract->createdAt = $proxy->getCreatedAt()->format(DateTimeInterface::ATOM);
        $contract->updatedAt = $proxy->getUpdatedAt()->format(DateTimeInterface::ATOM);
        $contract->ipProxy = $proxy->getIpProxy();
        $contract->ipReal = $proxy->getIpReal();
        $contract->port = $proxy->getPort();
        $contract->proxyType = $proxy->getProxyType()->value;
        $contract->proxyStatus = $proxy->getProxyStatus()->value;
        $contract->country = $proxy->getCountry();
        $contract->region = $proxy->getRegion();
        $contract->city = $proxy->getCity();
        $contract->timeout = $proxy->getTimeout();
        $contract->checkListId = $proxy->getCheckList()->getId()->getValue();

        return $contract;
    }
}
