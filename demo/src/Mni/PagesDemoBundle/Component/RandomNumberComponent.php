<?php

namespace Mni\PagesDemoBundle\Component;

use Mni\PagesBundle\Component\BaseComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Example of a component.
 */
class RandomNumberComponent extends BaseComponent
{
    protected $number;

    public function __construct(Request $request, ContainerInterface $container)
    {
        parent::__construct($request, $container);

        $this->number = $this->get('session')->get('number', '');
    }

    public function generateNumber()
    {
        $this->number = rand(0, 1000);
        $this->get('session')->set('number', $this->number);
    }

    public function getTemplate()
    {
        return 'MniPagesDemoBundle:RandomNumber:component.html.twig';
    }
}
