# Creating a page

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
