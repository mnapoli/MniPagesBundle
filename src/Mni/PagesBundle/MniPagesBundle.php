<?php

namespace Mni\PagesBundle;

use Mni\PagesBundle\DependencyInjection\Compiler\ControllerResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * MniPagesBundle initialization.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MniPagesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ControllerResolverPass());
    }
}
