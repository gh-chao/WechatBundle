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

        $container->setParameter('lilocon.security.enabled', $config['security']['enabled']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $sdk_definition = $container->getDefinition('lilocon.wechat.sdk');

        $sdk_argument = [
            'debug'   => $config['debug'],
            'app_id'  => $config['app_id'],
            'secret'  => $config['secret'],
            'token'   => $config['token'],
            'aes_key' => $config['aes_key'],
        ];

        if (array_key_exists('open_platform', $config)) {
            $sdk_argument['open_platform'] = $config['open_platform'];
        }
        if (array_key_exists('mini_program', $config)) {
            $sdk_argument['mini_program'] = $config['mini_program'];
        }
        if (array_key_exists('payment', $config)) {
            $sdk_argument['payment'] = $config['payment'];
        }

        $sdk_definition->replaceArgument(0, $sdk_argument);

        // 开启微信授权功能
        if ($config['security']['enabled']) {
            $loader->load('security_services.xml');
            $container->getDefinition('lilocon.security.authentication.wechat_provider')
                ->replaceArgument(0, new Reference($config['security']['user_provider_id']));
        }

        // 自定义缓存
        if ($config['cache']['overwrite']) {
            $sdk_definition->replaceArgument(2, new Reference($config['cache']['cache_id']));
        }

        // service_alias
        if (array_key_exists('service_alias', $config)) {
            $container->setAlias($config['service_alias'], 'lilocon.wechat.sdk');
        }
    }
}
