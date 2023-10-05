<?php

declare(strict_types=1);

namespace App\Common\Exception\Domain;

use Throwable;

class DomainException extends \DomainException
{
    public function __construct(
        string $message = '',
        ?int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, (int) $code, $previous);
    }
}
