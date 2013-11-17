<?php

namespace Mni\PagesBundle\Controller;

use Mni\PagesBundle\Component\Component;
use Mni\PagesBundle\Page\Page;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @throws HttpException Components can only be called through AJAX
     * @return mixed|boolean
     */
    public function getController(Request $request)
    {
        // Which action to call
        $action = $request->request->get('_action', 'render');
        if ($action && $action !== 'render') {
            // Do we render the page/component after the action has been called
            $render = $request->request->get('_render', false);
        } else {
            $render = false;
        }

        // Page?
        $pageName = $request->attributes->get('_page');
        if ($pageName) {
            $page = $this->create($pageName, $request);

            // Force render if request is not AJAX (else we have a blank page)
            if (!$request->isXmlHttpRequest()) {
                $render = true;
            }

            if ($render) {
                return $this->callActionAndRefreshPage($request, $page, $action);
            }

            return $this->callAction($request, $page, $action);
        }

        // Component?
        $componentName = $request->attributes->get('_component');
        if ($componentName) {
            // Check that we only make AJAX calls (a component is not a web page)
            if (!$request->isXmlHttpRequest()) {
                throw new HttpException("This method can only be called through POST requests");
            }

            $component = $this->create($componentName, $request);

            if ($render) {
                return $this->callActionAndRefreshComponent($request, $component, $action);
            }

            return $this->callAction($request, $component, $action);
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
     * @return Page|Component
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
     * Returns the controller that will call an action on a page or component.
     *
     * @param Request                $request
     * @param Page|Component $object
     * @param string                 $action
     * @return callable
     */
    private function callAction(Request $request, $object, $action)
    {
        $resolver = $this;

        return function () use ($resolver, $request, $object, $action) {
            // Call the action
            $controller = array($object, $action);
            $actionParameters = $resolver->getArguments($request, $controller);
            $response = call_user_func_array($controller, $actionParameters);

            // Automatically handle empty responses
            return $response ?: new Response();
        };
    }

    /**
     * Returns the controller that will call an action on a component and then render it.
     *
     * @param Request       $request
     * @param Component $component
     * @param string        $action
     * @return callable
     */
    private function callActionAndRefreshComponent(Request $request, Component $component, $action)
    {
        $resolver = $this;

        return function () use ($resolver, $request, $component, $action) {
            // Call the action
            $controller = array($component, $action);
            $actionParameters = $resolver->getArguments($request, $controller);
            call_user_func_array($controller, $actionParameters);

            // Call the render method
            $controller = array($component, 'render');
            $actionParameters = $resolver->getArguments($request, $controller);
            return call_user_func_array($controller, $actionParameters);
        };
    }

    /**
     * Returns the controller that will call an action on a page and then render it.
     *
     * @param Request  $request
     * @param Page $page
     * @param string   $action
     * @return callable
     */
    private function callActionAndRefreshPage(Request $request, Page $page, $action)
    {
        $resolver = $this;

        return function () use ($resolver, $request, $page, $action) {
            // Call the action
            $controller = array($page, $action);
            $actionParameters = $resolver->getArguments($request, $controller);
            call_user_func_array($controller, $actionParameters);

            // Redirect to the page if the page is a non-AJAX POST (to avoid re-post with F5)
            if ($request->isMethod('POST') && !$request->isXmlHttpRequest()) {
                return new RedirectResponse($request->getUri());
            }

            // Call the render method
            $controller = array($page, 'render');
            $actionParameters = $resolver->getArguments($request, $controller);
            return call_user_func_array($controller, $actionParameters);
        };
    }

    /**
     * Build an array of arguments using the request to call a method.
     *
     * @param Request                    $request
     * @param ReflectionFunctionAbstract $function
     *
     * @throws RuntimeException
     * @return array
     */
    private function doGetArguments(Request $request, ReflectionFunctionAbstract $function)
    {
        $arguments = array();

        foreach ($function->getParameters() as $reflectionParameter) {

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
                    if ($function instanceof ReflectionMethod) {
                        $repr = $function->getDeclaringClass()->getName() . '::' . $function->getName();
                    } else {
                        $repr = $function->getName();
                    }

                    throw new RuntimeException($repr . ' expect parameter ' . $reflectionParameter->getName());
                }
            }

            $arguments[] = $value;
        }

        return $arguments;
    }
}
