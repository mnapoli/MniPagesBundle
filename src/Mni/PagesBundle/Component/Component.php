<?php

namespace Mni\PagesBundle\Component;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract class representing a component.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class Component
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render()
    {
        $parameters = get_object_vars($this);

        unset($parameters['container']);

        $content = $this->get('templating')->render($this->getTemplate(), $parameters);

        return new Response($content);
    }

    /**
     * Returns true if the service id is defined.
     *
     * @param string $id The service id
     *
     * @return boolean true if the service id is defined, false otherwise
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Returns the name of the template to render this component.
     *
     * @return string
     */
    abstract public function getTemplate();

    /**
     * Returns an array of parameters needed to create this component.
     *
     * @return array
     */
    abstract public function getParameters();

    /**
     * Returns the component's route.
     *
     * @return string
     */
    abstract public function getRoute();
}
