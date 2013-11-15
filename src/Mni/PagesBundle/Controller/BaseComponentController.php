<?php

namespace Mni\PagesBundle\Controller;

use Mni\PagesBundle\Component\BaseComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseComponentController extends Controller
{
    /**
     * Route all requests to the component.
     *
     * @param Request $request
     * @return Response
     * @throws BadRequestHttpException
     */
    public function routeAction(Request $request)
    {
        $componentName = $this->getComponentName();

        /** @var BaseComponent $component */
        $component = new $componentName($request, $this->container);

        if ($request->isMethod('POST')) {
            return $this->routePost($component, $request);
        }

        return $this->routeGet($component);
    }

    /**
     * Route a GET request to the component.
     *
     * @param BaseComponent $page
     * @return Response
     */
    private function routeGet(BaseComponent $page)
    {
        return $page->render();
    }

    /**
     * Route a POST request to the component.
     *
     * @param BaseComponent $component
     * @param Request       $request
     * @throws BadRequestHttpException
     * @return Response
     */
    private function routePost(BaseComponent $component, Request $request)
    {
        $componentName = $this->getComponentName();

        $action = $request->get('_action');

        if ($action == null) {
            throw new BadRequestHttpException("HTTP parameter '_action' must be given");
        }
        if (! method_exists($component, $action)) {
            throw new BadRequestHttpException("Action $action doesn't exist on $componentName");
        }

        // Build the parameter array for the action
        $reflectionMethod = new \ReflectionMethod($componentName, $action);
        $reflectionParameters = $reflectionMethod->getParameters();
        $parameters = array();
        foreach ($reflectionParameters as $reflectionParameter) {
            $value = $request->get($reflectionParameter->getName());

            if ($value === null) {
                throw new BadRequestHttpException(
                    "Action $action expect parameter " . $reflectionParameter->getName()
                );
            }

            $parameters[] = $value;
        }

        // Call action
        $reflectionMethod->invokeArgs($component, $parameters);

        // Do we need to render the component?
        $refreshComponent = $request->get('_render');
        if ($refreshComponent === null) {
            throw new BadRequestHttpException("HTTP parameter '_render' must be given");
        }

        if (! $refreshComponent) {
            return new Response();
        }

        return $component->render();
    }

    /**
     * Returns the component's class name.
     *
     * @return string
     */
    abstract protected function getComponentName();
}
