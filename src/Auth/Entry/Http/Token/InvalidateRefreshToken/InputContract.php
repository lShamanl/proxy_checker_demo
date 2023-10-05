<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\InvalidateRefreshToken;

use IWD\Symfony\PresentationBundle\Interfaces\InputContractInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class InputContract implements InputContractInterface
{
    #[NotNull]
    public string $refreshToken;
}
