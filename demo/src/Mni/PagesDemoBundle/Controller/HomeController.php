<?php

namespace Mni\PagesDemoBundle\Controller;

use Mni\PagesBundle\Controller\BasePageController;

class HomeController extends BasePageController
{
    protected function getPageName()
    {
        return 'Mni\PagesDemoBundle\Page\HomePage';
    }
}
