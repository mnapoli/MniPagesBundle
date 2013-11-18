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
     * @param {object} data    Parameters used for calling the action
     * @param {bool}   refresh Should the call refresh the component? Does not issue an additional AJAX request.
     *
     * @returns {Deferred}
     */
    Pages.Component.prototype.callAction = function(data, refresh)
    {
        var self = this;

        // Add component's parameters
        for (var key in self.parameters) {
            if (self.parameters.hasOwnProperty(key) && !data.hasOwnProperty(key)) {
                data[key] = self.parameters[key];
            }
        }

        return $.post(self.route, data, function (html) {
            if (refresh) {
                $(self.dom).html(html);
            }
        });
    };

    /**
     * jQuery plugin for getting a component instance from a DOM object.
     *
     * @return {Pages.Component}
     */
    $.fn.component = function() {
        var component = this.data('component');

        if (component !== undefined) {
            return component;
        }

        return this.closest('[data-component]').data('component');
    };

})(jQuery);