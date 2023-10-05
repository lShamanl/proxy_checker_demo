<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy;

use App\Common\Service\Core\AggregateRoot;
use App\Common\Service\Core\EventsTrait;
use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use App\ProxyChecker\Domain\Proxy\Type\ProxyIdType;
use App\ProxyChecker\Domain\Proxy\Type\ProxyStatusType;
use App\ProxyChecker\Domain\Proxy\Type\ProxyTypeType;
use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Table(name: 'proxy_checker_proxies')]
#[Entity(repositoryClass: ProxyRepository::class)]
#[HasLifecycleCallbacks]
class Proxy implements AggregateRoot, ResourceInterface
{
    use EventsTrait;

    /** Entity ID */
    #[Id]
    #[Column(
        type: ProxyIdType::NAME,
        nullable: false,
    )]
    private ProxyId $id;

    /** Entity created at */
    #[Column(
        type: 'datetime_immutable',
        unique: false,
        nullable: false,
    )]
    private DateTimeImmutable $createdAt;

    /** Entity updated at */
    #[Column(
        type: 'datetime_immutable',
        unique: false,
        nullable: false,
    )]
    private DateTimeImmutable $updatedAt;

    /** IP прокси */
    #[Column(
        type: 'string',
        unique: false,
        nullable: false,
    )]
    private string $ipProxy;

    /** Реальный внешний IP прокси */
    #[Column(
        type: 'string',
        unique: false,
        nullable: false,
    )]
    private string $ipReal;

    /** Порт */
    #[Column(
        type: 'string',
        unique: false,
        nullable: false,
    )]
    private int $port;

    /** Тип прокси */
    #[Column(
        type: ProxyTypeType::NAME,
        unique: false,
        nullable: false,
        enumType: ProxyType::class,
    )]
    private ProxyType $proxyType;

    /** Статус работоспособности прокси */
    #[Column(
        type: ProxyStatusType::NAME,
        unique: false,
        nullable: false,
        enumType: ProxyStatus::class,
    )]
    private ProxyStatus $proxyStatus;

    /** Страна */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?string $country;

    /** Регион */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?string $region;

    /** Город */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?string $city;

    /** Таймаут */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?int $timeout;

    #[ManyToOne(
        targetEntity: CheckList::class,
        inversedBy: 'proxies',
    )]
    #[JoinColumn(
        name: 'proxy_id',
        referencedColumnName: 'id',
    )]
    private CheckList $checkList;

    public function __construct(
        ProxyId $id,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        string $ipProxy,
        string $ipReal,
        int $port,
        ProxyType $proxyType,
        ProxyStatus $proxyStatus,
        ?string $country,
        ?string $region,
        ?string $city,
        ?int $timeout,
        CheckList $checkList,
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->ipProxy = $ipProxy;
        $this->ipReal = $ipReal;
        $this->port = $port;
        $this->proxyType = $proxyType;
        $this->proxyStatus = $proxyStatus;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->timeout = $timeout;
        $this->checkList = $checkList;
    }

    public function edit(
        string $ipProxy,
        string $ipReal,
        int $port,
        ProxyType $proxyType,
        ProxyStatus $proxyStatus,
        ?string $country,
        ?string $region,
        ?string $city,
        ?int $timeout,
    ): void {
        $this->ipProxy = $ipProxy;
        $this->ipReal = $ipReal;
        $this->port = $port;
        $this->proxyType = $proxyType;
        $this->proxyStatus = $proxyStatus;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->timeout = $timeout;
    }

    public function getId(): ProxyId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getIpProxy(): string
    {
        return $this->ipProxy;
    }

    public function getIpReal(): string
    {
        return $this->ipReal;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getProxyType(): ProxyType
    {
        return $this->proxyType;
    }

    public function getProxyStatus(): ProxyStatus
    {
        return $this->proxyStatus;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function getCheckList(): CheckList
    {
        return $this->checkList;
    }

    #[PreUpdate]
    public function onUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getProxy(): string
    {
        return sprintf(
            '%s:%s',
            $this->ipProxy,
            $this->port,
        );
    }

    public function getDSN(): string
    {
        return sprintf(
            '%s://%s:%s',
            $this->proxyType->value,
            $this->ipProxy,
            $this->port
        );
    }

    public function setTimeout(?int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function setProxyType(ProxyType $proxyType): void
    {
        $this->proxyType = $proxyType;
    }

    public function setRealIp(string $realIp): void
    {
        $this->ipReal = $realIp;
    }
}
