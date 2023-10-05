<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/** @codeCoverageIgnore */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public const SKELETON_PATHS = [
        'Common/Resource/config',
        'Auth/Resource/config',
    ];
    public const PATHS = ConfigPaths::PATHS;
//    public const PATHS = [
//        // You can move the paths here
//    ];

    public function __construct(string $environment, bool $debug)
    {
        # You can set need timezone here:
//        date_default_timezone_set('Europe/Moscow');
        parent::__construct($environment, $debug);
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $this->configureContainerForPath($container, __DIR__ . '/../config');
        foreach ([...self::SKELETON_PATHS, ...self::PATHS] as $path) {
            $this->configureContainerForPath($container, __DIR__ . '/' . $path);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $this->configureRoutesForPath($routes, __DIR__ . '/../config');
        foreach ([...self::SKELETON_PATHS, ...self::PATHS] as $path) {
            $this->configureRoutesForPath($routes, __DIR__ . '/' . $path);
        }
    }

    public function configureContainerForPath(ContainerConfigurator $container, string $pathToConfig): void
    {
        $container->import($pathToConfig . '/{packages}/*.yaml');
        $container->import($pathToConfig . '/{packages}/' . $this->environment . '/*.yaml');

        if (is_file($pathToConfig . '/services.yaml')) {
            $container->import($pathToConfig . '/services.yaml');
            $container->import($pathToConfig . '/{services}_' . $this->environment . '.yaml');

            $container->import($pathToConfig . '/{services}/*.yaml');
            $container->import($pathToConfig . '/{services}/' . $this->environment . '/*.yaml');
        } elseif (is_file($path = $pathToConfig . '/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    public function configureRoutesForPath(RoutingConfigurator $routes, string $pathToConfig): void
    {
        $routes->import($pathToConfig . '/{routes}/' . $this->environment . '/*.yaml');
        $routes->import($pathToConfig . '/{routes}/**/*.yaml');

        if (is_file($pathToConfig . '/routes.yaml')) {
            $routes->import($pathToConfig . '/routes.yaml');
        } elseif (is_file($path = $pathToConfig . '/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
