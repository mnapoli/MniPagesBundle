# MniPagesBundle

Much awesomeness, very good, coming soon.

## What is this?

Typical PHP web frameworks are using controllers and actions:

- a request for a web page leads to an action in a controller
- an AJAX request leads to an action in a controller
- a form post leads to an action in a controller

This pattern is easy to understand, because every request just calls a controller action.
But this model doesn't feel natural: even though they all are HTTP requests, at a higher level
a web page and an AJAX request are not the same thing.

There is another way: component-based architecture.
This pattern introduces the concept of **Page**, **Component** and **Action**.

- a **Page** returns an HTML web page (wow!)
- a page can be composed of several reusable **Components** (that returns HTML fragments)
- you can call **Actions** on pages or components (through an AJAX request or form post)

### A small example

#### Pages

A page is a class:

```php
class HomePage extends Page
{
    protected $title = 'Hello!';
    protected $pageCountComponent;

    public function __construct()
    {
        $this->pageCountComponent = new PageCountComponent();
    }
}
```

and a template:

```html
{% extends '::layout.html.twig' %}

{% block content %}

    <h1>{{ title }}</h1>

    {{ component(pageCount) }}

{% endblock %}
```

Each property of the page is accessible in the template.

#### Components

A component is a class too:

```php
class PageCountComponent extends Component
{
    protected $count;

    public function __construct()
    {
        $this->count = $this->get('session')->get('pageCount', 0);
    }
}
```

and a template:

```html
<p>
    Page count: {{ count }}.
</p>
```

#### Actions

Let's add an action that resets the page count and refresh the component (not the page!):

```html
<p>
    Page count: {{ count }}.
    <button type="button" data-component-action="reset" data-component-refresh>Reset</button>
</p>
```

```php
class PageCountComponent extends Component
{
    protected $count;

    public function __construct()
    {
        $this->count = $this->get('session')->get('pageCount', 0);
    }

    public function reset()
    {
        $this->count = 0;
        $this->get('session')->set('pageCount', 0);
    }
}
```

Easy! The Javascript library already takes care of AJAX requests and refreshing components.


## Getting started

### Requirements

- jQuery

### Installation

- install via composer:

```json
{
    "require": {
        "mnapoli/pages-bundle": "*"
    }
}
```

- add the bundle to your `AppKernel.php`:

```php
public function registerBundles()
{
    return array(
        // ...
        new Mni\PagesBundle\MniPagesBundle(),
    );
}
```

- import the Javascript files in the layout:

```html
{% javascripts
    '@MniPagesBundle/Resources/public/js/*' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

## Documentation

- [Creating a page](docs/pages.md)
- [Javascript API](docs/javascript.md)

## Demo

The demo is in the `demo/` folder. It's a standard Symfony app, you can get it working easily:

```shell
$ cd demo/
$ composer install
$ app/console server:run
```

## License

MniPagesBundle is licensed under the MIT license. See the LICENSE file for more informations.
