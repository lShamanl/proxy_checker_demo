<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Client\Api\Action\Read;

use App\Auth\Domain\User\User;
use App\Auth\Entry\Http\Client\Api\Contract\User\CommonOutputContract;
use App\Auth\Infrastructure\Security\UserIdentity;
use IWD\Symfony\PresentationBundle\Dto\Input\OutputFormat;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Service\Presenter;
use IWD\Symfony\PresentationBundle\Service\QueryBus\Aggregate\Bus;
use IWD\Symfony\PresentationBundle\Service\QueryBus\Aggregate\Query;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Action
{
    public const NAME = 'api_client_app_auth_user_read';

    /**
     * @OA\Tag(name="Auth.User")
     * @OA\Response(
     *     response=200,
     *     description="Read query for User",
     *     @OA\JsonContent(
     *         allOf={
     *             @OA\Schema(ref=@Model(type=ApiFormatter::class)),
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     ref=@Model(type=CommonOutputContract::class)
     *                 ),
     *                 @OA\Property(
     *                     property="status",
     *                     example="200"
     *                 )
     *             )
     *         }
     *     )
     *  )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request"
     * ),
     * @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     * ),
     * @OA\Response(
     *     response=403,
     *     description="Forbidden"
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Resource Not Found"
     * )
     * @Security(name="Bearer")
     */
    #[Route(
        path: '/api/client/auth/users/{id}.{_format}',
        name: self::NAME,
        defaults: ['_format' => 'json'],
        methods: ['GET'],
    )]
    public function action(
        string $id,
        Bus $bus,
        OutputFormat $outputFormat,
        Presenter $presenter,
        UserIdentity $userIdentity,
    ): Response {
        if ($userIdentity->id !== $id) {
            throw new AccessDeniedException('Access Denied.');
        }
        $query = new Query($id, User::class);
        /** @var User $user */
        $user = $bus->query($query);

        return $presenter->present(
            data: ApiFormatter::prepare(
                CommonOutputContract::create($user)
            ),
            outputFormat: $outputFormat
        );
    }
}
