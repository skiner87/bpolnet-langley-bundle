<?php
/**
 * Created by PhpStorm.
 * User: skiner
 * Date: 09.01.18
 * Time: 16:56
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
        $def->replaceArgument(0, $container->getParameter('langleyConfig'));
    }

}