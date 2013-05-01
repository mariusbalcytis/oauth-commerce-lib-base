<?php


namespace Maba\OAuthCommerceClient;

use Maba\OAuthCommerceClient\DependencyInjection\ContainerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Factory
{

    public function createContainer($parameters = array())
    {
        $container = new ContainerBuilder(new ParameterBag($parameters));

        $extension = new ContainerExtension();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias());

        $container->compile();
        return $container;
    }
}