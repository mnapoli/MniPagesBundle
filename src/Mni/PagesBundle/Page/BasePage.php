<?php

namespace Mni\PagesBundle\Page;

use Mni\PagesBundle\Component\BaseComponent;

abstract class BasePage extends BaseComponent
{
    /**
     * Returns an array of parameters needed to create this component.
     *
     * @return array
     */
    public function getParameters()
    {
        return array();
    }
}
