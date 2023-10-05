<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\CheckList;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Model\ResourceInterface;

class CheckListRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(CheckList::class));
    }

    public function add(ResourceInterface $resource): void
    {
        $this->getEntityManager()->persist($resource);
    }

    public function remove(ResourceInterface $resource): void
    {
        $this->getEntityManager()->remove($resource);
    }

    public function findById(CheckListId $id): ?CheckList
    {
        /** @var CheckList|null $checkList */
        $checkList = $this->findOneBy(['id' => $id]);

        return $checkList;
    }

    public function hasById(CheckListId $id): bool
    {
        return null !== $this->findOneBy(['id' => $id]);
    }

    public function all(
        int $size = 100,
        int $offset = 0,
    ): Generator {
        $count = $this->createQueryBuilder('checkList')->select('count(1)')
            ->getQuery()
            ->getSingleScalarResult();

        while ($offset < $count) {
            /** @var CheckList[] $checkLists */
            $checkLists = $this->createQueryBuilder('checkList')
                ->addOrderBy('checkList.id', 'ASC')
                ->setFirstResult($offset)
                ->setMaxResults($size)
                ->getQuery()
                ->getResult()
            ;
            foreach ($checkLists as $checkList) {
                yield $checkList;
            }

            $offset += $size;
            $this->getEntityManager()->clear();
        }
    }
}
