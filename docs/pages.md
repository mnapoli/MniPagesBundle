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
    defaults: { _page: "Mni\\PagesDemoBundle\\Page\\HomePage" }
```

- create a page in a `Acme\AcmeBundle\Page` namespace:

```php
namespace Acme\AcmeBundle\Page;

use Mni\PagesBundle\Page\Page;

class HomePage extends Page
{
    protected $title = 'Hello!';

    public function getTemplate()
    {
        return 'AcmeBundle:Home:page.html.twig';
    }

    public function getRoute()
    {
        return 'home';
    }
}
```

`getTemplate()` returns the template of your page. It is used by the automatic implementation of the `render()`
method to render your page.

Every property is passed to the view:

```html
<h1>{{ title }}</h1>
<p>This is an example.</p>
```
