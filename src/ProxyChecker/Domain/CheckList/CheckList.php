<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\CheckList;

use App\Common\Service\Core\AggregateRoot;
use App\Common\Service\Core\EventsTrait;
use App\ProxyChecker\Domain\CheckList\Event\CheckListCreatedEvent;
use App\ProxyChecker\Domain\CheckList\Type\CheckListIdType;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Domain\Proxy\Proxy;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Sylius\Component\Resource\Model\ResourceInterface;

/** Студент */
#[Table(name: 'proxy_checker_check_lists')]
#[Entity(repositoryClass: CheckListRepository::class)]
#[HasLifecycleCallbacks]
class CheckList implements AggregateRoot, ResourceInterface
{
    use EventsTrait;

    /** Entity ID */
    #[Id]
    #[Column(
        type: CheckListIdType::NAME,
        nullable: false,
    )]
    private CheckListId $id;

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

    /** Дата основания */
    #[Column(
        type: 'datetime_immutable',
        unique: false,
        nullable: true,
    )]
    private ?DateTimeImmutable $endAt;

    /** Полезная нагрузка */
    #[Column(
        type: 'string',
        unique: false,
        nullable: false,
    )]
    private string $payload;

    /** Всего итераций */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?int $allIteration;

    /** Итераций, когда прокси оказалась рабочей */
    #[Column(
        type: 'string',
        unique: false,
        nullable: true,
    )]
    private ?int $successIteration;

    #[OneToMany(
        mappedBy: 'checkList',
        targetEntity: Proxy::class,
        cascade: ['all'],
        orphanRemoval: true,
    )]
    private Collection $proxies;

    public function __construct(
        CheckListId $id,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        ?DateTimeImmutable $endAt,
        string $payload,
        ?int $allIteration,
        ?int $successIteration,
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->endAt = $endAt;
        $this->payload = $payload;
        $this->allIteration = $allIteration;
        $this->successIteration = $successIteration;
        $this->proxies = new ArrayCollection();

        $this->recordEvent(
            new CheckListCreatedEvent(
                $this->id->getValue(),
            )
        );
    }

    public function edit(
        ?DateTimeImmutable $endAt,
        string $payload,
        ?int $allIteration,
        ?int $successIteration,
    ): void {
        $this->endAt = $endAt;
        $this->payload = $payload;
        $this->allIteration = $allIteration;
        $this->successIteration = $successIteration;
    }

    public function addProxy(Proxy $proxy): void
    {
        $this->proxies->add($proxy);
    }

    public function removeProxy(Proxy $proxy): void
    {
        $this->proxies->removeElement($proxy);
    }

    public function getId(): CheckListId
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

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getAllIteration(): ?int
    {
        return $this->allIteration;
    }

    public function getSuccessIteration(): ?int
    {
        return $this->successIteration;
    }

    /** @return Proxy[] */
    public function getProxies(): array
    {
        return $this->proxies->toArray();
    }

    #[PreUpdate]
    public function onUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function incrementSuccessIteration(): void
    {
        ++$this->successIteration;
    }
}
