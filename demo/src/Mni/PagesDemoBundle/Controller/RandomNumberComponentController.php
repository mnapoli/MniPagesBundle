<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesBundle\Controller\BaseComponentController;

class RandomNumberComponentController extends BaseComponentController
{
    /**
     * Returns the component's class name.
     *
     * @return string
     */
    protected function getComponentName()
    {
        return 'Mni\PagesDemoBundle\Component\RandomNumberComponent';
    }
}
