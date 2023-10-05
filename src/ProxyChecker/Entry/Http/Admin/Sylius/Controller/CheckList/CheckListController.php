<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\CheckList;

use App\ProxyChecker\Application\CheckList\UseCase\Create\Command as CreateCommand;
use App\ProxyChecker\Application\CheckList\UseCase\Create\Handler as CreateHandler;
use App\ProxyChecker\Application\CheckList\UseCase\Remove\Command as DeleteCommand;
use App\ProxyChecker\Application\CheckList\UseCase\Remove\Handler as DeleteHandler;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Entry\Http\Admin\Sylius\Controller\CheckList\Form\CreateType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(
    path: 'proxy-checker/check-lists',
    name: 'app_proxy_checker.check_list_',
)]
class CheckListController extends ResourceController
{
    #[Route(
        path: '/create/new',
        name: 'create',
        methods: ['GET', 'POST'],
    )]
    public function create(
        Request $request,
        CreateHandler $handler,
        TranslatorInterface $translator,
    ): Response {
        $formData = null;

        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $form = $this->createForm(CreateType::class, $formData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload = $form->getData();
            $result = $handler->handle(
                new CreateCommand(
                    payload: $payload['payload'],
                )
            );
            if ($result->isSuccess()) {
                $this->addFlash('success', $translator->trans('app.admin.ui.modules.proxy_checker.check_list.flash.success'));

                return $this->redirectToRoute('app_proxy_checker.check_list_show', ['id' => $result->checkList?->getId()->getValue()]);
            }
        }

        return $this->render(
            '@proxyChecker/admin/check_list/create.html.twig',
            [
                'metadata' => $this->metadata,
                'form' => $form->createView(),
                'resource' => $form->getData(),
                'configuration' => $configuration,
            ]
        );
    }

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
                id: new CheckListId($id)
            )
        );
        if ($result->isSuccess()) {
            $this->addFlash('success', $translator->trans('app.admin.ui.modules.proxy_checker.check_list.flash.success'));
        }
        if ($result->isCheckListNotExists()) {
            $this->addFlash('error', $translator->trans('app.admin.ui.modules.proxy_checker.check_list.flash.check_list_not_exists'));
        }

        return $this->redirectToRoute('app_proxy_checker.check_list_index');
    }
}
