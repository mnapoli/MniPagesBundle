<?php

namespace Mni\PagesDemoBundle\Page;

use Mni\PagesBundle\Page\BasePage;

/**
 * Home page
 */
class HomePage extends BasePage
{
    public function getTemplate()
    {
        return 'MniPagesDemoBundle:Home:page.html.twig';
    }
}
