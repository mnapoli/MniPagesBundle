<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesDemoBundle\Page\HomePage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $page = new HomePage($request, $this->container);

        return $page->render();
    }
}
