<?php

namespace Mni\PagesBundle\Page;

use Mni\PagesBundle\Component\Component;

/**
 * Abstract class representing a page.
 *
 * A page can be viewed as a special kind of component, that's why it inherits from Component.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
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
