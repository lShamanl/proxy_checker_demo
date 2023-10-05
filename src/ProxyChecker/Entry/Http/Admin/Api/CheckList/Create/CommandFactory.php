<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Create;

use App\ProxyChecker\Application\CheckList\UseCase\Create\Command;
use DateTimeImmutable;

class CommandFactory
{
    public function create(InputContract $inputContract): Command
    {
        if (!isset(
            $inputContract->payload,
        )) {
            throw new \Exception('Check NotNull asserts in ' . InputContract::class . ' for required properties');
        }

        return new Command(
            payload: $inputContract->payload,
        );
    }
}
