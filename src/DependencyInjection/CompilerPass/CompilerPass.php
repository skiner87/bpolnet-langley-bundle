<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class CompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $def = $container->getDefinition('BpolNet\Bundle\LangleyBundle\Service\Langley');
        $def->setArgument(0, $container->getParameter('langleyConfig'));
    }

}
