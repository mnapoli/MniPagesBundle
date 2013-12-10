/**
 * Pages bundle js library.
 *
 * Requires jQuery.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */

var Pages = Pages || {};

(function($) {

    /**
     * Component class.
     *
     * @param {object} dom        DOM object representing the div containing the component.
     * @param {string} route      Route (URL) to call the component.
     * @param {object} parameters Parameters that build the component, used when calling an action on the component.
     *
     * @constructor
     */
    Pages.Component = function(dom, route, parameters)
    {
        this.dom = dom;
        this.route = route;
        this.parameters = parameters;

        // Attach to DOM object
        $(this.dom).data('component', this);
    };

    /**
     * Call an action on the component.
     *
     * @param {string} action  Action to call.
     * @param {object} data    Parameters used for calling the action.
     * @param {bool}   refresh Should the call refresh the component? Does not issue an additional AJAX request.
     *
     * @returns {Deferred}
     */
    Pages.Component.prototype.callAction = function(action, data, refresh)
    {
        var self = this;
        data = data || {};

        // Add component's parameters
        for (var key in this.parameters) {
            if (this.parameters.hasOwnProperty(key) && !data.hasOwnProperty(key)) {
                data[key] = this.parameters[key];
            }
        }

        data._action = action;
        data._render = refresh;

        var deferred = $.post(this.route, data, function(html) {
            if (refresh) {
                $(self.dom).html(html);
            }
        });
        deferred.fail(Pages.errorHandler);
        return deferred;
    };

    /**
     * jQuery plugin for getting a component instance from a DOM object.
     *
     * @return {Pages.Component}
     */
    $.fn.component = function()
    {
        var component = this.data('component');

        if (component !== undefined) {
            return component;
        }

        return this.closest('[data-component]').data('component');
    };

})(jQuery);
