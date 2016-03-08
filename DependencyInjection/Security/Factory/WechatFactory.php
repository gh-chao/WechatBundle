<?php

namespace Lilocon\WechatBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class WechatFactory implements SecurityFactoryInterface
{

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wechat.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('lilocon.security.authentication.wechat_provider'))
        ;

        $listenerId = 'security.authentication.listener.wechat.'.$id;
        $container->setDefinition(
            $listenerId,
            new DefinitionDecorator('lilocon.security.authentication.wechat_listener')
        );

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'wechat_login';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('authorize_path')->isRequired()->end()
            ->end()
        ;
    }
}