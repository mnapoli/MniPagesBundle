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
     * Page class.
     *
     * @constructor
     */
    Pages.Page = function()
    {
    };

    /**
     * Call an action on the page.
     *
     * @param {string} action Action to call.
     * @param {object} data   Parameters used for calling the action
     * @param {bool}   ajax   Should the call be AJAX? Or a page refresh?
     *
     * @returns {Deferred}
     */
    Pages.Page.prototype.callAction = function(action, data, ajax)
    {
        data = data || {};
        data._action = action;

        if (ajax) {
            // Ajax post
            return $.post(window.location.pathname, data);
        }

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

        // Unused deferred object (because the page will be refreshed)
        return $.Deferred();
    }

})(jQuery);
