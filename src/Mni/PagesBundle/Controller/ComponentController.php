<?php

namespace Mni\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ComponentController extends Controller
{
    public function routeAction(Request $request)
    {
        list($bundle, $componentName) = explode(':', $request->get('_componentName'));
        $controller = "$bundle:{$componentName}Component:default";

        // Forward POST parameters
        $parameters = $this->getRequest()->request->all();

        return $this->forward($controller, $parameters);
    }
}
