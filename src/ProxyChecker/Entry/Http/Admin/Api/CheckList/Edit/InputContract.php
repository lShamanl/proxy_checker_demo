<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Edit;

use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;

class InputContract implements InputContractInterface
{
    #[NotNull]
    #[Positive]
    public ?string $id = null;

    public ?string $endAt = null;

    public ?string $payload = null;

    public ?int $allIteration = null;

    public ?int $successIteration = null;
}
