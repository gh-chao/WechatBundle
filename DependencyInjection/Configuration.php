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
                ->scalarNode('app_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('app_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('token')->isRequired()->end()
                ->scalarNode('cache_provider_id')->isRequired()->end()
                ->scalarNode('user_class')->isRequired()->end()
                ->scalarNode('alias')->end()
                ->arrayNode('payment')
                    ->children()
                        ->scalarNode('merchant_id')->isRequired()->end()
                        ->scalarNode('key')->isRequired()->end()
                        ->scalarNode('cert_path')->isRequired()->end()
                        ->scalarNode('key_path')->isRequired()->end()
                        ->scalarNode('notify_url')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }


}
