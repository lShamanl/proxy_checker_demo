<?php

declare(strict_types=1);

namespace App\Common\Service\Core;

interface AggregateRoot
{
    public function releaseEvents(): array;
}
