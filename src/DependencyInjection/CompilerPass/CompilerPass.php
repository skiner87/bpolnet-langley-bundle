<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle\DependencyInjection\CompilerPass;

use BpolNet\Bundle\LangleyBundle\Service\Langley;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $def = $container->getDefinition(Langley::class);
        $def->setArgument(0, $container->getParameter('langleyConfig'));
    }
}
