<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\InvalidateRefreshToken;

use App\Auth\Infrastructure\Security\JwtTokenizer;
use App\Auth\Infrastructure\Security\RefreshTokenCache;
use App\Common\Exception\Domain\DomainException;
use IWD\Symfony\PresentationBundle\Dto\Input\OutputFormat;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Service\Presenter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Action extends AbstractController
{
    /**
     * @OA\Tag(name="Auth.Token")
     * @OA\Post(
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref=@Model(type=InputContract::class)
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Refresh token",
     *     @OA\JsonContent(
     *          allOf={
     *              @OA\Schema(ref=@Model(type=ApiFormatter::class)),
     *              @OA\Schema(type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      type="object",
     *                      example={"refreshToken": "Invalidated done"}
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      example="200"
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    #[Route(
        path: '/api/token/invalidate-refresh-token',
        name: 'token.invalidateRefreshToken',
        methods: ['POST']
    )]
    public function invalidateRefreshToken(
        InputContract $contract,
        JwtTokenizer $jwtTokenizer,
        Presenter $presenter,
        RefreshTokenCache $refreshTokenCache
    ): Response {
        $userId = $jwtTokenizer->getUserIdByRefreshToken($contract->refreshToken);
        if (false === $refreshTokenCache->validate($userId, $contract->refreshToken)) {
            throw new DomainException('Token is not valid', 400);
        }

        $refreshTokenCache->invalidate($userId, $contract->refreshToken);

        return $presenter->present(
            data: ApiFormatter::prepare(
                messages: ['Invalidated done']
            ),
            outputFormat: new OutputFormat('json')
        );
    }
}
