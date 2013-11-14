<?php

namespace Mni\PagesBundle\Twig;

use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Provides Twig integration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class PagesExtension extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $environment;

    /**
     * @var FragmentHandler
     */
    private $handler;

    public function __construct(FragmentHandler $handler)
    {
        $this->handler = $handler;
    }

    public function getName()
    {
        return 'pages_extension';
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('component', [$this, 'component'], array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders a component.
     *
     * @param string $component
     * @param array  $parameters
     * @return string
     */
    public function component($component, array $parameters = array())
    {
        $controller = $component . 'Component:default';
        $uri = new ControllerReference($controller, $parameters);

        $html = $this->handler->render($uri);

        return '<div data-component="' . $component . '">' . $html . '</div>';
    }
}
