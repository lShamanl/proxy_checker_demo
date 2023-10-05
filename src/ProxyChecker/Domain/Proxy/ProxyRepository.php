<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy;

use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProxyRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Proxy::class));
    }

    public function add(ResourceInterface $resource): void
    {
        $this->getEntityManager()->persist($resource);
    }

    public function remove(ResourceInterface $resource): void
    {
        $this->getEntityManager()->remove($resource);
    }

    public function findById(ProxyId $id): ?Proxy
    {
        /** @var Proxy|null $proxy */
        $proxy = $this->findOneBy(['id' => $id]);

        return $proxy;
    }

    public function hasById(ProxyId $id): bool
    {
        return null !== $this->findOneBy(['id' => $id]);
    }

    public function all(
        int $size = 100,
        int $offset = 0,
    ): Generator {
        $count = $this->createQueryBuilder('proxy')->select('count(1)')
            ->getQuery()
            ->getSingleScalarResult();

        while ($offset < $count) {
            /** @var Proxy[] $proxies */
            $proxies = $this->createQueryBuilder('proxy')
                ->addOrderBy('proxy.id', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults($size)
                ->getQuery()
                ->getResult()
            ;
            foreach ($proxies as $proxy) {
                yield $proxy;
            }

            $offset += $size;
            $this->getEntityManager()->clear();
        }
    }
}
