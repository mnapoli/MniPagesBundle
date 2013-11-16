<?php

namespace Mni\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RouteComponentActionController extends Controller
{
    public function routeAction(Request $request)
    {
        $controller = $request->get('_componentName') . "Controller::route";

        // Forward POST parameters
        $parameters = $this->getRequest()->request->all();

        return $this->forward($controller, $parameters);
    }
}
