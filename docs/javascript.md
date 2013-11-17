# Javascript API

The javascript API in namespaces in the `Pages` namespace.

## `Pages.Page`

### Current page

The object representing the current page can be retrieved using:

```javascript
Pages.currentPage;
```

### Methods

- `callAction(data, ajax)`: calls an action of the page
  - `data`: parameters (object) to pass to the action
  - `ajax`: if true, makes an AJAX call, else refresh the page

## `Pages.Component`

### Retrieving components

- `$().component()`: jQuery plugin that returns the component object (`Pages.Component`).

```javascript
$('#myForm').component();
```

This will return the component in which `#myForm` is declared.

### Methods

- `callAction(data, refresh)`: calls an action of the component
  - `data`: parameters (object) to pass to the action
  - `refresh`: if true, refresh the component
