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

        $definition = $container->getDefinition('lilocon.wechat.sdk');

        $argument = array(
            'debug'  => false,
            'app_id' => $config['app_id'],
            'secret' => $config['app_secret'],
            'token'  => $config['token'],
        );

        if (array_key_exists('payment', $config)) {
            $argument['payment'] = $config['payment'];
        }

        $definition->replaceArgument(0, $argument);
        $definition->replaceArgument(1, new Reference($config['cache_provider_id']));

        // alias
        if (array_key_exists('alias', $config)) {
            $container->setAlias($config['alias'], 'lilocon.wechat.sdk');
        }
    }
}
