<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection;

use BpolNet\Bundle\LangleyBundle\Exception\LangleyException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class LangleyConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('langley');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('secret')
                    ->isRequired()
                ->end()
                ->scalarNode('translationsPath')
                    ->isRequired()
                    ->defaultValue('%kernel.root_dir%/translations')
                    ->validate()
                    ->ifString()
                        ->then(function($value) {
                            if (!is_dir($value)) {
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
                        ->then(function($value) {
                            if (!is_dir($value)) {
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
