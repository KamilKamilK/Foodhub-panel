<?php declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir() . '/config';
        $container->import($confDir . '/packages/*.{yaml,yml}');
        $container->import($confDir . '/packages/' . $this->environment . '/*.{yaml,yml}');
        $container->import($confDir . '/services.{yaml,yml}');
        $container->import($confDir . '/services_' . $this->environment . '.{yaml,yml}', null, 'not_found');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/routes/' . $this->environment . '/*.{yaml,yml}', 'glob');
        $routes->import($confDir . '/routes/*.{yaml,yml}', 'glob');

        // Auto-discover controllers from all DDD contexts
        foreach (glob($this->getProjectDir() . '/src/*/Infrastructure/Http') ?: [] as $dir) {
            $routes->import($dir . '/', 'attribute');
        }
        // Shared controllers
        if (is_dir($this->getProjectDir() . '/src/Shared/Infrastructure/Http')) {
            $routes->import($this->getProjectDir() . '/src/Shared/Infrastructure/Http/', 'attribute');
        }
    }
}
