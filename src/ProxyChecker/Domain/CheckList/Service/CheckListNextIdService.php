<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\CheckList\Service;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CheckListNextIdService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @throws Exception */
    public function allocateId(): CheckListId
    {
        $id = $this->em
            ->getConnection()
            ->prepare("SELECT nextval('proxy_checker_check_list_seq')")
            ->executeQuery()
            ->fetchOne();

        return new CheckListId((string) $id);
    }
}
