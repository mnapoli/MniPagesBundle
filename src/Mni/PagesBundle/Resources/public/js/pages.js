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

/**
 * Page class.
 * @constructor
 */
Pages.Page = function () {
    this.callAction = function (data, ajax) {
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

    this.callAction = function (data, refresh) {
        data.name = self.name;
        $.post(Pages.componentRoute, data, function (html) {
            if (refresh) {
                $(self.dom).html(html);
            }
        });
    }
};

// Create current page
Pages.currentPage = new Pages.Page();

// Create a component object for each component
Pages.components = [];
$('[data-component]').each(function (index, object) {
    var name = $(object).attr('data-component');
    Pages.components.push(new Pages.Component(name, object));
});

$('body').on('click', '[data-page-action]', function(e) {
    e.preventDefault();

    var action = $(this).data('page-action');
    var refreshPage = ($(this).attr('data-page-refresh') !== undefined);

    var data = {
        action: action
    };

    Pages.currentPage.callAction(data, !refreshPage);
});

$('[data-component]').on('click', '[data-component-action]', function(e) {
    e.preventDefault();

    var component = $(this).closest('[data-component]').data('component');

    var action = $(this).data('component-action');
    var refreshComponent = ($(this).attr('data-component-refresh') !== undefined);

    var data = {
        action: action
    };

    component.callAction(data, refreshComponent);
});
