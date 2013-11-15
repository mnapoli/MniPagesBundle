<?php

namespace Mni\PagesDemoBundle\Page;

use Mni\PagesBundle\Page\BasePage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Home page
 */
class HomePage extends BasePage
{
    protected $title;

    public function __construct(Request $request, ContainerInterface $container)
    {
        parent::__construct($request, $container);

        $this->title = $this->get('session')->get('title', 'Welcome!');
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->get('session')->set('title', $title);
    }

    public function getTemplate()
    {
        return 'MniPagesDemoBundle:Home:page.html.twig';
    }
}
