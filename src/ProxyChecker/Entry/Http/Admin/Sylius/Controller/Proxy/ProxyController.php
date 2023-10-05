<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\Proxy;

use App\ProxyChecker\Application\Proxy\UseCase\Create\Command as CreateCommand;
use App\ProxyChecker\Application\Proxy\UseCase\Create\Handler as CreateHandler;
use App\ProxyChecker\Application\Proxy\UseCase\Edit\Command as EditCommand;
use App\ProxyChecker\Application\Proxy\UseCase\Edit\Handler as EditHandler;
use App\ProxyChecker\Application\Proxy\UseCase\Remove\Command as DeleteCommand;
use App\ProxyChecker\Application\Proxy\UseCase\Remove\Handler as DeleteHandler;
use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\CheckList\CheckListRepository;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use App\ProxyChecker\Domain\Proxy\Proxy;
use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\Proxy\Form\CreateType;
use App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\Proxy\Form\EditType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(
    path: 'proxy-checker/proxies',
    name: 'app_proxy_checker.proxy_',
)]
class ProxyController extends ResourceController
{
    #[Route(
        path: '/{id}/delete',
        name: 'delete',
        methods: ['POST'],
    )]
    public function delete(
        string $id,
        DeleteHandler $handler,
        TranslatorInterface $translator,
    ): Response {
        $result = $handler->handle(
            new DeleteCommand(
                id: new ProxyId($id)
            )
        );
        if ($result->isSuccess()) {
            $this->addFlash('success', $translator->trans('app.admin.ui.modules.proxy_checker.proxy.flash.success'));
        }
        if ($result->isProxyNotExists()) {
            $this->addFlash('error', $translator->trans('app.admin.ui.modules.proxy_checker.proxy.flash.proxy_not_exists'));
        }

        return $this->redirectToRoute('app_proxy_checker.proxy_index');
    }
}
