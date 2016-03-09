<?php

namespace Lilocon\WechatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class LiloconWechatExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('lilocon.wechat.user_class', $config['user_class']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $sdk_definition = $container->getDefinition('lilocon.wechat.sdk');
        $sdk_definition->replaceArgument(0,
            array(
                'debug'  => false,
                'app_id' => $config['app_id'],
                'secret' => $config['app_secret'],
                'token'  => $config['token'],
            )
        );

        $sdk_definition->addMethodCall('__set', array(
            'cache',
            new Reference($config['cache_provider_id'])
        ));

    }
}
