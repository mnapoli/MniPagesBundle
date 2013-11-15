<?php

namespace Mni\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RouteComponentActionController extends Controller
{
    public function routeAction(Request $request)
    {
        list($bundle, $componentName) = explode(':', $request->get('_componentName'));
        $controller = "$bundle:{$componentName}Component:route";

        // Forward POST parameters
        $parameters = $this->getRequest()->request->all();

        return $this->forward($controller, $parameters);
    }
}