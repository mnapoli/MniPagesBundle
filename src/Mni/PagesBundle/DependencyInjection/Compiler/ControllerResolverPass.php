<?php

namespace Mni\PagesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Compiler pass to wrap and replace the default controller resolver by ours.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
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
