<?php

namespace Mni\PagesBundle\Twig;

use Mni\PagesBundle\Component\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(FragmentHandler $handler, UrlGeneratorInterface $urlGenerator)
    {
        $this->handler = $handler;
        $this->urlGenerator = $urlGenerator;
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
            new Twig_SimpleFunction('component', array($this, 'renderComponent'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Renders a component.
     *
     * When the Response is a StreamedResponse, the content is streamed immediately
     * instead of being returned.
     *
     * @param Component $component
     *
     * @throws \RuntimeException when the Response is not successful
     * @return string|null The Response content or null when the Response is streamed
     */
    public function renderComponent(Component $component)
    {
        $response = $component->render();

        if (!$response->isSuccessful()) {
            throw new \RuntimeException(sprintf(
                'Error when rendering component %s (Status code is %s).',
                get_class($component),
                $response->getStatusCode()
            ));
        }

        $route = $this->urlGenerator->generate($component->getRoute());
        $parameters = htmlspecialchars(json_encode($component->getParameters()), ENT_QUOTES);

        // Wrap the content into HTML tags
        $wrapBegin = "<div data-component data-component-route='$route' data-component-parameters='$parameters'>";
        $wrapEnd = "</div>";

        if (!$response instanceof StreamedResponse) {
            return $wrapBegin . $response->getContent() . $wrapEnd;
        }

        echo $wrapBegin;
        $response->sendContent();
        echo $wrapEnd;
    }
}
