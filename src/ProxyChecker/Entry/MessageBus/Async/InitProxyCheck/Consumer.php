<?php

namespace App\ProxyChecker\Entry\MessageBus\Async\InitProxyCheck;

use App\ProxyChecker\Application\Proxy\UseCase\Create\Command;
use App\ProxyChecker\Application\Proxy\UseCase\Create\Handler;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Entry\MessageBus\Message\Command\InitProxyCheckMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class Consumer
{
    public function __construct(
        private Handler $handler,
    ) {
    }

    public function __invoke(InitProxyCheckMessage $message): void
    {
        $this->handler->handle(
            new Command(
                checkListId: new CheckListId($message->checkListId),
                proxy: $message->proxy,
            ),
        );
    }
}
