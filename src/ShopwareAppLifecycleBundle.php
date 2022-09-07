<?php

namespace BAL\AppLifecycleBundle;

use Shopware\Core\Kernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class ShopwareAppLifecycleBundle extends Bundle
{
    public function build(ContainerBuilder $container) : void
    {
        $this->registerContainerFile($container);
    }

    protected function registerContainerFile(ContainerBuilder $container) : void
    {
        $fileLocator      = new FileLocator($this->getPath());
        $loaderResolver   = new LoaderResolver(
            [
                new XmlFileLoader($container, $fileLocator),
                new YamlFileLoader($container, $fileLocator),
                new PhpFileLoader($container, $fileLocator),
                new DirectoryLoader($container, $fileLocator),
                new ClosureLoader($container),
            ]
        );
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        foreach ($this->getServicesFilePathArray($this->getPath() . '/Resources/config/services.*') as $path) {
            $delegatingLoader->load($path);
        }
        if ($container->getParameter('kernel.environment') === 'test') {
            foreach ($this->getServicesFilePathArray($this->getPath() . '/Resources/config/services_test.*') as $testPath) {
                $delegatingLoader->load($testPath);
            }
        }
    }

    private function getServicesFilePathArray(string $path) : array
    {
        $pathArray = glob($path);

        if ($pathArray === false) {
            return [];
        }

        return $pathArray;
    }

}
