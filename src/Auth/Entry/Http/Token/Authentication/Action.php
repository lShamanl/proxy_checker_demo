<?php

declare(strict_types=1);

namespace App\Auth\Entry\Http\Token\Authentication;

use App\Auth\Domain\User\UserRepository;
use App\Auth\Entry\Http\Token\TokenOutputContract;
use App\Auth\Infrastructure\Security\JwtTokenizer;
use App\Auth\Infrastructure\Security\RefreshTokenCache;
use App\Auth\Infrastructure\Security\UserIdentity;
use App\Common\Exception\Domain\DomainException;
use IWD\Symfony\PresentationBundle\Dto\Input\OutputFormat;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Service\Presenter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
     *     description="Authentication",
     *     @OA\JsonContent(
     *          allOf={
     *              @OA\Schema(ref=@Model(type=ApiFormatter::class)),
     *              @OA\Schema(type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      ref=@Model(type=TokenOutputContract::class)
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
    #[Route(path: '/api/token/authentication', name: 'token.authentication', methods: ['POST'])]
    public function authentication(
        UserRepository $userRepository,
        InputContract $contract,
        UserPasswordHasherInterface $passwordHasher,
        JwtTokenizer $jwtTokenizer,
        Presenter $presenter,
        RefreshTokenCache $refreshTokenCache
    ): Response {
        $user = $userRepository->findByEmail($contract->email);
        if (null === $user) {
            throw new DomainException('Invalid credentials', 401);
        }

        if (!$passwordHasher->isPasswordValid($user, $contract->password)) {
            throw new DomainException('Invalid credentials', 401);
        }

        $userIdentity = new UserIdentity(
            id: $user->getId()->getValue(),
            username: $user->getEmail(),
            password: $user->getPasswordHash(),
            display: $user->getEmail(),
            role: $user->getRole(),
        );

        $outputContract = TokenOutputContract::create(
            access: $jwtTokenizer->generateAccessToken($userIdentity),
            refresh: $refresh = $jwtTokenizer->generateRefreshToken($userIdentity)
        );

        $refreshTokenCache->cache($userIdentity->id, $refresh);

        return $presenter->present(
            data: ApiFormatter::prepare(
                data: $outputContract,
                messages: [
                    'Success authentication',
                ]
            ),
            outputFormat: new OutputFormat('json')
        );
    }
}
