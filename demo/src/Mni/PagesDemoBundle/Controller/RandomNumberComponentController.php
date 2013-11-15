<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesBundle\Controller\BaseComponentController;

class RandomNumberComponentController extends BaseComponentController
{
    protected function getComponentName()
    {
        return 'Mni\PagesDemoBundle\Component\RandomNumberComponent';
    }
}
