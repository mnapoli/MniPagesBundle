<?php

namespace Mni\PagesBundle\Controller;

use Mni\PagesBundle\Component\BaseComponent;
use Mni\PagesBundle\Page\BasePage;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * ControllerResolver.
 *
 * This implementation uses the '_page' or '_component' request attribute to determine
 * the pag or component to call and uses the request attributes to determine
 * the method arguments.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ControllerResolver implements ControllerResolverInterface
{
    /**
     * @var ControllerResolverInterface
     */
    private $defaultResolver;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function __construct(
        ControllerResolverInterface $defaultResolver,
        ContainerInterface $container,
        LoggerInterface $logger = null
    ) {
        $this->defaultResolver = $defaultResolver;
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Returns the Controller callable associated with a Request.
     *
     * @param Request $request
     *
     * @return mixed|boolean
     */
    public function getController(Request $request)
    {
        $pageName = $request->attributes->get('_page');
        $componentName = $request->attributes->get('_component');
        $action = $request->request->get('_action', 'render');

        if ($pageName) {
            $page = $this->create($pageName, $request);

            return array($page, $action);
        }

        if ($componentName) {
            $component = $this->create($componentName, $request);

            return array($component, $action);
        }

        // Use the default resolver
        return $this->defaultResolver->getController($request);
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request
     * @param mixed   $controller A PHP callable
     *
     * @throws RuntimeException When value for argument given is not provided
     * @return array
     */
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $r);
    }

    /**
     * @param string  $className Page or component's class name
     * @param Request $request
     *
     * @throws RuntimeException
     * @return BasePage|BaseComponent
     */
    private function create($className, Request $request)
    {
        // TODO handle MyBundle:Home short notation (like for controllers)
        // Build the parameter array for the constructor
        $reflectionClass = new ReflectionClass($className);
        $parameters = $this->doGetArguments($request, $reflectionClass->getConstructor());

        return $reflectionClass->newInstanceArgs($parameters);
    }

    /**
     * Build an array of arguments using the request to call a method.
     *
     * @param Request          $request
     * @param ReflectionMethod $reflectionMethod
     *
     * @throws RuntimeException
     * @return array
     */
    private function doGetArguments(Request $request, ReflectionMethod $reflectionMethod)
    {
        $arguments = array();

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {

            // Inject container
            $parameterType = $reflectionParameter->getClass();
            $containerClass = 'Symfony\Component\DependencyInjection\ContainerInterface';
            if ($parameterType && $parameterType->implementsInterface($containerClass)) {
                $arguments[] = $this->container;
                continue;
            }

            // Inject request
            if ($parameterType && $parameterType->isInstance($request)) {
                $arguments[] = $request;
                continue;
            }

            $value = $request->get($reflectionParameter->getName());

            if ($value === null) {
                // Default value if defined
                if ($reflectionParameter->isDefaultValueAvailable()) {
                    $arguments[] = $reflectionParameter->getDefaultValue();
                } else {
                    throw new RuntimeException(
                        $reflectionMethod->getDeclaringClass()->getName() . '::' . $reflectionMethod->getName()
                        . ' expect parameter ' . $reflectionParameter->getName()
                    );
                }
            }

            $arguments[] = $value;
        }

        return $arguments;
    }
}
