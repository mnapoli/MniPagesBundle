<?php

namespace Mni\PagesDemoBundle\Component;

use Mni\PagesBundle\Component\BaseComponent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Example of a component.
 */
class RandomNumberComponent extends BaseComponent
{
    protected $id;

    protected $number;

    public function __construct($id, ContainerInterface $container)
    {
        parent::__construct($container);

        $this->id = $id;
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

    public function getRoute()
    {
        return 'random_number_component';
    }

    /**
     * Returns an array of parameters needed to create this component.
     *
     * @return array
     */
    public function getParameters()
    {
        return array(
            'id' => $this->id,
        );
    }
}
