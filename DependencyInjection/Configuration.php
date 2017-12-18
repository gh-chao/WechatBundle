<?php

namespace Lilocon\WechatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lilocon_wechat');

        $rootNode
            ->children()
                ->booleanNode('debug')->isRequired()->end()

                ->scalarNode('app_id')->isRequired()->end()
                ->scalarNode('secret')->isRequired()->end()
                ->scalarNode('token')->isRequired()->end()
                ->scalarNode('aes_key')->isRequired()->end()

                ->arrayNode('open_platform')
                    ->children()
                        ->scalarNode('app_id')->isRequired()->end()
                        ->scalarNode('secret')->isRequired()->end()
                        ->scalarNode('token')->isRequired()->end()
                        ->scalarNode('aes_key')->isRequired()->end()
                    ->end()
                ->end()

                ->arrayNode('mini_program')
                    ->children()
                        ->scalarNode('app_id')->isRequired()->end()
                        ->scalarNode('secret')->isRequired()->end()
                        ->scalarNode('token')->isRequired()->end()
                        ->scalarNode('aes_key')->end()
                    ->end()
                ->end()

                ->arrayNode('payment')
                    ->children()
                        ->scalarNode('merchant_id')->isRequired()->end()
                        ->scalarNode('key')->isRequired()->end()
                        ->scalarNode('cert_path')->end()
                        ->scalarNode('key_path')->end()
                        ->scalarNode('device_info')->end()
                        ->scalarNode('sub_app_id')->end()
                        ->scalarNode('sub_merchant_id')->end()
                    ->end()
                ->end()

                ->arrayNode('security')
                    ->children()
                        ->booleanNode('enabled')->isRequired()->end()
                        ->scalarNode('user_provider_id')->isRequired()->end()
                    ->end()
                ->end()

                ->arrayNode('cache')
                    ->children()
                        ->booleanNode('overwrite')->isRequired()->end()
                        ->scalarNode('cache_id')->isRequired()->end()
                    ->end()
                ->end()

                ->scalarNode('service_alias')->end()
            ->end()
        ;

        return $treeBuilder;
    }


}
