<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Remove;

use App\Common\Service\Metrics\AdapterInterface;
use App\ProxyChecker\Application\CheckList\UseCase\Remove\Result;

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
        if ($result->isCheckListNotExists()) {
            $this->metrics->createCounter(
                name: str_replace('.', '_', Action::NAME) . ':check_list_not_exists',
                help: 'check list not exists'
            )->inc();
        }
    }
}
