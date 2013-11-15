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
```

- import the Javascript files into the page and configure it, for example in the `<head>` tag:

```twig
{% javascripts
    '@MniPagesBundle/Resources/public/js/*' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}

<script type="text/javascript">
    var Pages = Pages || {};
    Pages.componentRoute = '{{ path('mni_pages_component') }}';
</script>
```
