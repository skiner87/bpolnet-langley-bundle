<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $def = $container->getDefinition('BpolNet\Bundle\LangleyBundle\Service\Langley');
        $def->setArgument(0, $container->getParameter('langleyConfig'));
    }

}