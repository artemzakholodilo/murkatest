<?php

namespace MailerBundle\DependencyInjetion;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        /*$treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acme_social');

        $rootNode
            ->children()
                ->arrayNode('twitter')
                    ->children()
                        ->integerNode('client_id')->end()
                        ->scalarNode('client_secret')->end()
                    ->end()
                ->end()// twitter
            ->end();

        return $treeBuilder;*/
    }
}