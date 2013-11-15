<?php

namespace Mni\PagesBundle\Controller;

use Mni\PagesBundle\Page\BasePage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BasePageController extends Controller
{
    /**
     * Route all requests to the page.
     *
     * @param Request $request
     * @return Response
     * @throws BadRequestHttpException
     */
    public function routeAction(Request $request)
    {
        $pageName = $this->getPageName();

        /** @var BasePage $page */
        $page = new $pageName($request, $this->container);

        if ($request->isMethod('POST')) {
            return $this->routePost($page, $request);
        }

        return $this->routeGet($page);
    }

    /**
     * Route a GET request to the page.
     *
     * @param BasePage $page
     * @return Response
     */
    private function routeGet(BasePage $page)
    {
        return $page->render();
    }

    /**
     * Route a POST request to the page.
     *
     * @param BasePage $page
     * @param Request  $request
     * @throws BadRequestHttpException
     * @return Response
     */
    private function routePost(BasePage $page, Request $request)
    {
        $pageName = $this->getPageName();

        $action = $request->get('_action');

        if ($action == '') {
            throw new BadRequestHttpException("HTTP parameter '_action' must be given");
        }
        if (! method_exists($page, $action)) {
            throw new BadRequestHttpException("Action $action doesn't exist on page $pageName");
        }

        // Build the parameter array for the action
        $reflectionMethod = new \ReflectionMethod($pageName, $action);
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
        $reflectionMethod->invokeArgs($page, $parameters);

        // If the page is AJAX, returns an empty 200 response
        if ($request->isXmlHttpRequest()) {
            return new Response();
        }

        // Redirect to the page if the page is not AJAX
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * Returns the page class name.
     *
     * @return string
     */
    abstract protected function getPageName();
}
