<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\Proxy\Remove;

use App\ProxyChecker\Application\Proxy\UseCase\Remove\Command;
use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;

class CommandFactory
{
    public function create(InputContract $inputContract): Command
    {
        if (!isset(
            $inputContract->id,
        )) {
            throw new \Exception('Check NotNull asserts in ' . InputContract::class . ' for required properties');
        }

        return new Command(
            id: new ProxyId($inputContract->id),
        );
    }
}
