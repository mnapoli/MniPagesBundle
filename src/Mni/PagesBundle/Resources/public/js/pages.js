/**
 * Pages bundle js library.
 *
 * Requires jQuery.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */

var Pages = Pages || {};

/**
 * Override to define your error handler.
 */
Pages.errorHandler = function(jqXHR) {};

(function($) {

    /**
     * jQuery plugin to subscribe to events when actions are called (buttons)
     */
    $.fn.action = function(onSuccess) {
        if (onSuccess !== undefined) {
            // onSuccess given: register event handler
            this.on('actionSuccess', onSuccess);
        } else {
            // onSuccess not given: trigger the action call
            this.trigger('action');
        }

        return this;
    };

})(jQuery);

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

    var body = $('body');

    // Page > link or button
    body.on('click', 'a[data-page-action], button[data-page-action]', function(e) {
        e.preventDefault();
        $(this).action();
    });
    body.on('action', 'a[data-page-action], button[data-page-action]', function() {
        var action = $(this).data('page-action');
        var refreshPage = ($(this).attr('data-page-refresh') !== undefined);

        var deferred = Pages.currentPage.callAction(action, {}, !refreshPage);

        // Events
        var source = $(this);
        deferred.done(function() {
            source.trigger('actionSuccess');
        });
        deferred.fail(function() {
            source.trigger('actionError');
        });
    });

    // Page > form
    body.on('submit', 'form[data-page-action]', function(e) {
        e.preventDefault();
        $(this).action();
    });
    body.on('action', 'form[data-page-action]', function() {
        var action = $(this).data('page-action');
        var refreshPage = ($(this).attr('data-page-refresh') !== undefined);

        // Form data
        var data = {};
        $.each($(this).serializeArray(), function(index, input) {
            data[input.name] = input.value;
        });

        var deferred = Pages.currentPage.callAction(action, data, !refreshPage);

        // Events
        var source = $(this);
        deferred.done(function() {
            source.trigger('actionSuccess');
        });
        deferred.fail(function() {
            source.trigger('actionError');
        });
    });

    // Component > link or button
    allComponents.on('click', 'a[data-component-action], button[data-component-action]', function(e) {
        e.preventDefault();
        $(this).action();
    });
    allComponents.on('action', 'a[data-component-action], button[data-component-action]', function() {
        var component = $(this).component();

        var action = $(this).data('component-action');
        var refreshComponent = ($(this).attr('data-component-refresh') !== undefined);

        var deferred = component.callAction(action, {}, refreshComponent);

        // Events
        var source = $(this);
        deferred.done(function() {
            source.trigger('actionSuccess');
        });
        deferred.fail(function() {
            source.trigger('actionError');
        });
    });

    // Component > form
    allComponents.on('submit', 'form[data-component-action]', function(e) {
        e.preventDefault();
        $(this).action();
    });
    allComponents.on('action', 'form[data-component-action]', function() {
        var component = $(this).component();

        var action = $(this).data('component-action');
        var refreshComponent = ($(this).attr('data-component-refresh') !== undefined);

        // Form data
        var data = {};
        $.each($(this).serializeArray(), function(index, input) {
            data[input.name] = input.value;
        });

        var deferred = component.callAction(action, data, refreshComponent);

        // Events
        var source = $(this);
        deferred.done(function() {
            source.trigger('actionSuccess');
        });
        deferred.fail(function() {
            source.trigger('actionError');
        });
    });

});
