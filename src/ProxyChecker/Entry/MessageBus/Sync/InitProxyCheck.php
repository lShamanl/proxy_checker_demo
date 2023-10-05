<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\MessageBus\Sync;

use App\ProxyChecker\Domain\CheckList\CheckListRepository;
use App\ProxyChecker\Domain\CheckList\Event\CheckListCreatedEvent;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Entry\MessageBus\Message\Command\InitProxyCheckMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: CheckListCreatedEvent::class, method: 'produce')]
readonly class InitProxyCheck
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CheckListRepository $checkListRepository,
    ) {
    }

    public function produce(CheckListCreatedEvent $event): void
    {
        $checkList = $this->checkListRepository->findById(new CheckListId($event->id));
        $checkListIdScalar = $checkList->getId()->getValue();
        $proxiesPayload = $checkList->getPayload();
        foreach (explode(PHP_EOL, $proxiesPayload) as $proxy) {
            $this->messageBus->dispatch(
                new InitProxyCheckMessage(
                    checkListId: $checkListIdScalar,
                    proxy: trim($proxy),
                )
            );
        }
    }
}
