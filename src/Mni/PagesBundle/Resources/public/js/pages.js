/**
 * Pages bundle js library.
 *
 * Requires Pages.componentRoute defined.
 * Requires jQuery.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */

var Pages = Pages || {};

/**
 * To define.
 * @type {string}
 */
Pages.componentRoute = '';
Pages.getComponentRoute = function() {
    if (Pages.componentRoute == '') {
        throw "'Pages.componentRoute' must be defined and have as value the route: 'mni_pages_component'";
    }
    return Pages.componentRoute;
};

jQuery(function($) {

    /**
     * Page class.
     * @constructor
     */
    Pages.Page = function () {
        this.callAction = function(data, ajax) {
            if (ajax) {
                // Ajax post
                $.post(window.location.pathname, data);
            } else {
                // Non-ajax post
                var form = $('<form/>', {
                    action: '',
                    method: 'post'
                });
                $.each(data, function(name, value) {
                    form.append($('<input/>', {
                        type: 'hidden',
                        name: name,
                        value: value
                    }));
                });
                form.appendTo('body').submit();
            }
        }
    };

    /**
     * Component class.
     * @param {string} name Name of the component, in the form: "AcmeBundle:Name"
     * @param dom
     * @constructor
     */
    Pages.Component = function (name, dom) {
        var self = this;

        this.name = name;
        this.dom = dom;

        // Attach to DOM
        $(this.dom).data('component', this);

        this.callAction = function(data, refresh) {
            data._componentName = self.name;
            $.post(Pages.getComponentRoute(), data, function (html) {
                if (refresh) {
                    $(self.dom).html(html);
                }
            });
        }
    };

    // Create current page
    Pages.currentPage = new Pages.Page();

    // Create a component object for each component
    var allComponents = $('[data-component]');
    Pages.components = [];
    allComponents.each(function(index, object) {
        var name = $(object).attr('data-component');
        Pages.components.push(new Pages.Component(name, object));
    });

    // Page > link or button
    $('body').on('click', 'a[data-page-action], button[data-page-action]', function(e) {
        e.preventDefault();

        var action = $(this).data('page-action');
        var refreshPage = ($(this).attr('data-page-refresh') !== undefined);

        var data = {
            _action: action
        };

        Pages.currentPage.callAction(data, !refreshPage);
    });

    // Page > form
    $('body').on('submit', 'form[data-page-action]', function(e) {
        e.preventDefault();

        var action = $(this).data('page-action');
        var refreshPage = ($(this).attr('data-page-refresh') !== undefined);

        var data = {
            _action: action
        };

        // Form data
        $.each($(this).serializeArray(), function(index, input) {
            data[input.name] = input.value;
        });

        Pages.currentPage.callAction(data, !refreshPage);
    });

    // Component > link or button
    allComponents.on('click', 'a[data-component-action], button[data-component-action]', function(e) {
        e.preventDefault();

        var component = $(this).closest('[data-component]').data('component');

        var action = $(this).data('component-action');
        var refreshComponent = ($(this).attr('data-component-refresh') !== undefined);

        var data = {
            _action: action,
            _render: refreshComponent
        };

        component.callAction(data, refreshComponent);
    });

    // Component > form
    allComponents.on('submit', 'form[data-component-action]', function(e) {
        e.preventDefault();

        var component = $(this).closest('[data-component]').data('component');

        var action = $(this).data('component-action');
        var refreshComponent = ($(this).attr('data-component-refresh') !== undefined);

        var data = {
            _action: action,
            _render: refreshComponent
        };

        // Form data
        $.each($(this).serializeArray(), function(index, input) {
            data[input.name] = input.value;
        });

        component.callAction(data, refreshComponent);
    });

});
