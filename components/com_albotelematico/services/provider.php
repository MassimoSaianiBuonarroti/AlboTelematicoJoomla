<?php

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Service provider per com_albotelematico
 */
return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        // Namespace del tuo componente: deve combaciare con quello nel manifest
        $namespace = '\\AlboTelematico\\Component\\Albotelematico';

        // Registra la MVCFactory per il tuo componente
        $container->registerServiceProvider(new MVCFactory($namespace));

        // Registra il dispatcher del componente
        $container->registerServiceProvider(new ComponentDispatcherFactory($namespace));

        // Registra il componente stesso
        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new MVCComponent(
                    $container->get(ComponentDispatcherFactoryInterface::class)
                );

                $component->setMVCFactory(
                    $container->get(MVCFactoryInterface::class)
                );

                return $component;
            }
        );
    }
};
