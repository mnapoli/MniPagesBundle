<?php

namespace Mni\PagesBundle\Controller;

use Mni\PagesBundle\Component\BaseComponent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BasePageController extends BaseComponentController
{
    /**
     * Returns the component's class name.
     *
     * @return string
     */
    protected function getComponentName()
    {
        return $this->getPageName();
    }

    /**
     * Returns the page's class name.
     *
     * @return string
     */
    abstract protected function getPageName();

    /**
     * Returns the response for a POST request.
     *
     * @param BaseComponent $component
     * @param Request       $request
     * @return Response
     * @throws BadRequestHttpException
     */
    protected function returnPostResponse(BaseComponent $component, Request $request)
    {
        // If the page is AJAX, returns an empty 200 response
        if ($request->isXmlHttpRequest()) {
            return new Response();
        }

        // Redirect to the page if the page is not AJAX
        return $this->redirect($request->getUri());
    }
}
