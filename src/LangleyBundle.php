<?php

declare(strict_types=1);

namespace BpolNet\Bundle\LangleyBundle;

use BpolNet\Bundle\LangleyBundle\DependencyInjection\CompilerPass\CompilerPass;
use BpolNet\Bundle\LangleyBundle\DependencyInjection\LangleyExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Marek Krokwa <marek.krokwa@gmail.com>
 */
class LangleyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CompilerPass);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new LangleyExtension();
    }
}
