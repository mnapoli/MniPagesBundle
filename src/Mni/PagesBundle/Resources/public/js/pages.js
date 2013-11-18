/**
 * Pages bundle js library.
 *
 * Requires jQuery.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */

var Pages = Pages || {};

jQuery(function($) {

    // Create current page
    Pages.currentPage = new Pages.Page();

    // Create a component object for each component
    var allComponents = $('[data-component]');
    Pages.components = [];
    allComponents.each(function(index, object) {
        var route = $(object).attr('data-component-route');
        var parameters = JSON.parse($(object).attr('data-component-parameters'));
        Pages.components.push(new Pages.Component(object, route, parameters));
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

        var component = $(this).component();

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

        var component = $(this).component();

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
