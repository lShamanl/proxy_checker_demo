<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\Service;

use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ProxyNextIdService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /** @throws Exception */
    public function allocateId(): ProxyId
    {
        $id = $this->em
            ->getConnection()
            ->prepare("SELECT nextval('proxy_checker_proxy_seq')")
            ->executeQuery()
            ->fetchOne();

        return new ProxyId((string) $id);
    }
}
