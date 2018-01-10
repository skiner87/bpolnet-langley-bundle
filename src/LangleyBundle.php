<?php

/**
 * This file is part of the BpolNet company package.
 *
 * Marek Krokwa <marek.krokwa@bpol.net>
 */

namespace BpolNet\Bundle\LangleyBundle;

use BpolNet\Bundle\LangleyBundle\DependencyInjection\CompilerPass\CompilerPass;
use BpolNet\Bundle\LangleyBundle\DependencyInjection\LangleyExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LangleyBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPass);
    }

    public function getContainerExtension()
    {
        return new LangleyExtension();
    }


}