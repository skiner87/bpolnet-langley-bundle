<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection;

use BpolNet\Bundle\LangleyBundle\Exception\LangleyException;
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
                ->scalarNode('secret')
                    ->isRequired()
                ->end()
                ->scalarNode('translationsPath')
                    ->isRequired()
                    ->defaultValue('%kernel.root_dir%/translations')
                    ->validate()
                    ->ifString()
                        ->then(function($value)
                        {
                            if (!is_dir($value))
                            {
                                throw new LangleyException(sprintf('Translations directory is not readable (%s)', $value));
                            }

                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('translationsJsPath')
                    ->isRequired()
                    ->defaultValue('%kernel.root_dir%/assets/js')
                    ->validate()
                    ->ifString()
                        ->then(function($value)
                        {
                            if (!is_dir($value))
                            {
                                throw new LangleyException(sprintf('Translations directory is not readable (%s)', $value));
                            }

                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('translationsJsFile')
                    ->isRequired()
                    ->defaultValue('030.Lang.js')
                ->end()
                ->scalarNode('variableJsObject')
                    ->defaultValue('Trans')
                    ->info('Name of variable which will be used in javascript. Ex. `Trans` => var Trans.en = {}')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
