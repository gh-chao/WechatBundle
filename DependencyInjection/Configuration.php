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
                ->scalarNode('cache_provider')->isRequired()->end()
                ->scalarNode('entity_name')->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }


}
