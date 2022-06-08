<?php

namespace Sonata\PageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class BlockContainerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('sonata.page.block.context_manager')
            ->addMethodCall('addGlobal', [
                'sonata_page',
                new Reference('sonata.page.block.context_manager'),
            ]);
    }
}
