# Javascript API

The Javascript API is in the `Pages` namespace.


## `Pages.Page`

### Current page

The object representing the current page can be retrieved using:

```javascript
Pages.currentPage;
```

### Methods

- `callAction(action, data, ajax)`: calls an action of the page
  - `action`: action name
  - `data`: parameters (object) to pass to the action
  - `ajax`: if true, makes an AJAX call, else refresh the page
  - Returns a [`Deferred` object](http://api.jquery.com/category/deferred-object/)


## `Pages.Component`

### Retrieving components

- `$().component()`: jQuery plugin that returns the component object (`Pages.Component`).

```javascript
$('#myForm').component();
```

This will return the component in which `#myForm` is declared.

### Methods

- `callAction(action, data, refresh)`: calls an action of the component
  - `action`: action name
  - `data`: parameters (object) to pass to the action
  - `refresh`: if true, refresh the component
  - Returns a [`Deferred` object](http://api.jquery.com/category/deferred-object/)

## Triggering and listening to actions

Example:

```html
<button id="resetNumbers" type="button" data-page-action="resetNumbers">
    Reset the numbers without refreshing
</button>
```

Attaching an event to listen to an action:

```javascript
// Attach a handler for when the action is executed
$("#resetNumbers").action(function() {
    alert("Done!");
});
```

Triggering manually the action (just like if you clicked the button):

```javascript
$("#resetNumbers").action();
```

Also works for `<form>` elements.


## Error handling

When an action errors, the AJAX request will fail.
You can attach a custom error handler (the default one doesn't do anything) by defining:

```javascript
Pages.errorHandler = function(jqXHR) {
    console.log(jqXHR);
    alert("An error occurred");
};
```

The parameter of the function is a [jQuery jqXHR object](http://api.jquery.com/jQuery.ajax/#jqXHR)
since this function is bound to the jQuery AJAX request as a "fail" handler.
