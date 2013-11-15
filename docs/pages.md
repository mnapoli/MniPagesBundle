# Pages

## HTML & JS API

- `data-page-action="generateNumber"`

Can be used on a link, button or form.

```html
<button type="button" data-page-action="generateNumber">Generate number</button>
```

Will call `generateNumber` on the page through an AJAX POST request.

- `data-page-refresh`

```html
<button type="button" data-page-action="generateNumber" data-page-refresh>Generate number</button>
```

Will call `generateNumber` and refresh the page.

Example with a form:

```html
<form class="form-inline" data-page-action="setTitle" data-page-refresh>
    <input type="text" name="title">
    <button type="submit">Update title</button>
</form>
```


## Creating a page

- create the route:

```yaml
home:
    pattern:  /
    defaults: { _controller: "AcmeBundle:Home:route" }
```

- create the controller:

```php
class HomeController extends \Mni\PagesBundle\Controller\BasePageController
{
    protected function getPageName()
    {
        return 'Mni\PagesDemoBundle\Page\HomePage';
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
    protected $title = 'Hello!';

    public function getTemplate()
    {
        return 'AcmeBundle:Home:page.html.twig';
    }
}
```

`getTemplate()` returns the template of your page.

Every property is passed to the view:

```html
<h1>{{ title }}</h1>
<p>This is an example.</p>
```
