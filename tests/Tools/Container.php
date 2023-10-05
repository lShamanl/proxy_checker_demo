<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Container
{
    private readonly ContainerInterface $container;

    /**
     * @psalm-suppress ContainerDependency
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     */
    public function get(string $id)
    {
        /** @var T $instance */
        $instance = $this->container->get($id);

        return $instance;
    }
}
