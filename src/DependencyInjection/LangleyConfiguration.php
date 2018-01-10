<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class LangleyConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('langley');

        /** @noinspection PhpUndefinedMethodInspection */
        $rootNode
            ->children()
                ->scalarNode('secret')->isRequired()->end()
                ->scalarNode('translationsPath')->isRequired()->defaultValue('translations')->end()
                ->scalarNode('translationsJsPath')->isRequired()->defaultValue('assets/js')->end()
                ->scalarNode('translationsJsFile')->isRequired()->defaultValue('030.Lang.js')->end()
            ->end()
        ;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
