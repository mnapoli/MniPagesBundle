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

- import the Javascript files in the layout:

```html
{% javascripts
    '@MniPagesBundle/Resources/public/js/*' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
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
