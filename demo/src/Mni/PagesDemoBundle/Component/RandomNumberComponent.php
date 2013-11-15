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
    protected $id;

    protected $number;

    public function __construct(Request $request, ContainerInterface $container)
    {
        parent::__construct($request, $container);

        $this->id = $request->get('id');
        $this->number = $this->get('session')->get('number' . $this->id, 0);
    }

    public function generateNumber()
    {
        $this->setNumber(rand(0, 1000));
    }

    public function setNumber($number)
    {
        $this->number = $number;
        $this->get('session')->set('number' . $this->id, $number);
    }

    public function getTemplate()
    {
        return 'MniPagesDemoBundle:RandomNumber:component.html.twig';
    }
}
