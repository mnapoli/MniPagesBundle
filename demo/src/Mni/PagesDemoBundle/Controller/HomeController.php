<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesDemoBundle\Page\HomePage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class HomeController extends Controller
{
    public function defaultAction(Request $request)
    {
        $page = new HomePage($request, $this->container);

        // POST -> action
        if ($request->isMethod('POST')) {
            $action = $request->get('action');

            if ($action == '') {
                throw new BadRequestHttpException("HTTP parameter 'action' must be given");
            }

            // Call action
            $page->$action();

            return $this->redirect($this->generateUrl('home'));
        }

        // GET or others
        return $page->render();
    }
}
