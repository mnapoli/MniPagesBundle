<?php

namespace Mni\PagesDemoBundle\Page;

use Mni\PagesBundle\Page\BasePage;
use Mni\PagesDemoBundle\Component\RandomNumberComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Home page
 */
class HomePage extends BasePage
{
    protected $title;

    /**
     * @var RandomNumberComponent
     */
    protected $component1;

    /**
     * @var RandomNumberComponent
     */
    protected $component2;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->title = $this->get('session')->get('title', 'Welcome!');

        $this->component1 = new RandomNumberComponent(1, $container);
        $this->component2 = new RandomNumberComponent(2, $container);
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->get('session')->set('title', $title);
    }

    public function resetNumbers()
    {
        $this->component1->setNumber(0);
        $this->component2->setNumber(0);
    }

    public function getTemplate()
    {
        return 'MniPagesDemoBundle:Home:page.html.twig';
    }

    public function getRoute()
    {
        return 'home_page';
    }
}
