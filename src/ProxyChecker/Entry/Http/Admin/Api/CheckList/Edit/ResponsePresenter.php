<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Edit;

use App\ProxyChecker\Application\CheckList\UseCase\Edit\Result;
use App\ProxyChecker\Entry\Http\Admin\Api\Contract\CheckList\CommonOutputContract;
use DomainException;
use IWD\Symfony\PresentationBundle\Dto\Input\OutputFormat;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Service\Presenter;
use Symfony\Component\HttpFoundation\Response;

class ResponsePresenter
{
    public function __construct(private readonly Presenter $presenter)
    {
    }

    public function present(
        Result $result,
        OutputFormat $outputFormat,
    ): Response {
        if ($result->isSuccess()) {
            return $this->presenter->present(
                data: ApiFormatter::prepare(
                    data: null !== $result->checkList ? CommonOutputContract::create($result->checkList) : null,
                    messages: ['success']
                ),
                outputFormat: $outputFormat,
                status: Response::HTTP_OK,
            );
        }
        if ($result->isCheckListNotExists()) {
            return $this->presenter->present(
                data: ApiFormatter::prepare(
                    data: null !== $result->checkList ? CommonOutputContract::create($result->checkList) : null,
                    messages: ['check list not exists']
                ),
                outputFormat: $outputFormat,
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        throw new DomainException('Unexpected termination of the script');
    }
}
