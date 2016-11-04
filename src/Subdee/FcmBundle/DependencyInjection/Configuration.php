<?php

namespace Subdee\FcmBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('fcm');
        $rootNode
            ->children()
            ->arrayNode('push')
            ->children()
            ->scalarNode('api_key')->end()
            ->end()
            ->end()
            ->arrayNode('search')
            ->children()
            ->scalarNode('api_key')->end()
            ->scalarNode('search_id')->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
