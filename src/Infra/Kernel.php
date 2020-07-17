<?php

namespace Infra;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__.'/../../config/packages/*.yaml');
        $container->import(__DIR__.'/../../config/packages/' . $this->environment . '/*.yaml');

        $container->import(__DIR__.'/../../config/services.yaml');
        if (is_file(__DIR__.'/../../config/services_' . $this->environment . '.yaml')) {
            $container->import(__DIR__.'/../../config/services_' . $this->environment . '.yaml');
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        if (is_dir(__DIR__.'/../../config/routes/' . $this->environment)) {
            $routes->import(__DIR__.'/../../config/routes/' . $this->environment . '/*.yaml');
        }
        $routes->import(__DIR__.'/../../config/routes/*.yaml');
    }
}
