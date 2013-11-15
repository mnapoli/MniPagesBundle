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

## Creating a new page

- create the route:

```yaml
home:
    pattern:  /
    defaults: { _controller: "AcmeBundle:Home:default" }
```

- create the controller:

```php
class HomeController extends Controller
{
    public function defaultAction(Request $request)
    {
        $page = new HomePage($request, $this->container);

        // POST -> action
        if ($request->isMethod('POST')) {
            $action = $request->get('action');

            if ($action == '') {
                throw new BadRequestHttpException("HTTP parameter 'action' must be given");
            }

            // Call action
            $page->$action();

            return $this->redirect($this->generateUrl('home'));
        }

        return $page->render();
    }
}
```

The `default` action catches all requests to the page.

For a `GET` request, it will display the page.
For a `POST` request, it will call an action on the page and then redirect to the page.

- create a page in a `Acme\AcmeBundle\Page` namespace:

```php
namespace Acme\AcmeBundle\Page;

class HomePage extends BasePage
{
    public function getTemplate()
    {
        return 'AcmeBundle:Home:page.html.twig';
    }
}
```

`getTemplate()` returns the template of your page.
