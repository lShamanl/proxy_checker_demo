<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Create;

use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;

class InputContract implements InputContractInterface
{
    public ?string $endAt = null;

    public ?string $payload = null;

    public ?int $allIteration = null;

    public ?int $successIteration = null;
}
