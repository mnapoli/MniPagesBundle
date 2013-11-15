<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesDemoBundle\Component\RandomNumberComponent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RandomNumberComponentController extends Controller
{
    public function defaultAction(Request $request)
    {
        $component = new RandomNumberComponent($request, $this->container);

        // POST -> action
        if ($request->isMethod('POST')) {
            $action = $request->get('action');

            if ($action == '') {
                throw new BadRequestHttpException("HTTP parameter 'action' must be given");
            }

            // Call action
            $component->$action();
        }

        return $component->render();
    }
}
