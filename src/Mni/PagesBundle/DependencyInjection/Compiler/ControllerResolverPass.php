<?php

namespace Mni\PagesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ControllerResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Wrap the default controller resolver and replace it
        $definition = $container->getDefinition('controller_resolver');
        $container->setDefinition('default.controller_resolver', $definition);

        $container->setAlias('controller_resolver', 'mnipages.controller_resolver');
    }
}
