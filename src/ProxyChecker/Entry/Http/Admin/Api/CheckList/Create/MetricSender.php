<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Create;

use App\Common\Service\Metrics\AdapterInterface;
use App\ProxyChecker\Application\CheckList\UseCase\Create\Result;

/** @codeCoverageIgnore */
class MetricSender
{
    public function __construct(private readonly AdapterInterface $metrics)
    {
    }

    public function send(Result $result): void
    {
        if ($result->isSuccess()) {
            $this->metrics->createCounter(
                name: str_replace('.', '_', Action::NAME) . ':success',
                help: 'success'
            )->inc();
        }
    }
}
