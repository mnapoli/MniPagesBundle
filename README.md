# MniPagesBundle

Much awesomeness, very good, coming soon.

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

- import the routes into `app/config/routing.yml`:

```yaml
mni_pages:
    resource: "@MniPagesBundle/Resources/config/routing.yml"
    prefix:   /mnipages # you can prefix those routes to avoid conflicts with yours
```

- import the Javascript files into the page and configure it, for example in the `<head>` tag:

```html
{% javascripts
    '@MniPagesBundle/Resources/public/js/*' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}

<script type="text/javascript">
    var Pages = Pages || {};
    Pages.componentRoute = "{{ path('mni_pages_component') }}";
</script>
```

## Documentation

- [Creating a page](docs/pages.md)

## Demo

The demo is in the `demo/` folder. It's a standard Symfony app, you can get it working easily:

```shell
$ cd demo/
$ composer install
$ app/console server:run
```

## License

MniPagesBundle is licensed under the MIT license. See the LICENSE file for more informations.
