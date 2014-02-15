<?php
/**
 * @Author: Michał Kurzeja, Accesto
 * User: michal
 * Date: 24.09.13
 * Time: 10:18
 */

namespace Gregwar\ImageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gregwar_image');

        $rootNode
            ->children()
            ->scalarNode('cache_dir')->defaultValue('cache')->end()
            ->booleanNode('throw_exception')->defaultFalse()->end()
            ->scalarNode('fallback_image')->defaultValue(null)->end()
            ->scalarNode('web_dir')->defaultValue('%kernel.root_dir%/../web')->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
