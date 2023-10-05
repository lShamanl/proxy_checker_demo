<?php

declare(strict_types=1);

namespace App\Common\Entry\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckAction extends AbstractController
{
    public const NAME = 'health_check';

    #[Route(path: '/health-check', name: self::NAME, methods: ['GET'])]
    public function root(): JsonResponse
    {
        return new JsonResponse([
            'api' => [
                'version' => '1.0',
            ],
        ]);
    }
}
