<?php

namespace L3\Bundle\LdapUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('l3_security');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('roles')
                    ->beforeNormalization()
                        ->ifTrue(function($v) { return $v === null; })
                        ->then(function($v) { return array(); })
                    ->end()
                    ->prototype('scalar')->end()
                    ->defaultValue(array())
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
