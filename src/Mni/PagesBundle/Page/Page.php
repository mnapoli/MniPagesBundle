<?php

namespace Mni\PagesBundle\Page;

use Mni\PagesBundle\Component\Component;

abstract class Page extends Component
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
