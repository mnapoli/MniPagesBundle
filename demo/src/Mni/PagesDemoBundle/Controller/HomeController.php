<?php

namespace Mni\PagesDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('MniPagesDemoBundle:Home:index.html.twig');
    }
}
