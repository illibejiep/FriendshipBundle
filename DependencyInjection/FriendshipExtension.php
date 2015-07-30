<?php

namespace FriendshipBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FriendshipExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        foreach($config as $key => $value)
            $container->setParameter('friendship.' . $key, $value);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        if(!$container->hasExtension('doctrine'))
            return;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $container->getExtensionConfig('friendship'));

        $forInsertion = array(
            'orm' => array(
                'resolve_target_entities' => array(
                    'Symfony\Component\Security\Core\User\UserInterface' => $config['user_entity'],
                )
            )
        );
        $container->prependExtensionConfig('doctrine', $forInsertion);
    }


}
