/* 
 * SyntaxThemes Validation
 * 
 * @author Ryan Haworth
 */
(function($, window, document, undefined) {

    $.synthValidation = function(element, options) {

        var obj = this;

        var defaults = {
            errorContainer: jQuery(this).find('.validation-summary')
        };

        obj.options = jQuery.extend({}, defaults, options);
        this.initialize(element);
    };

    $.synthValidation.prototype = {
        initialize: function(element) {

            var obj = this;

            jQuery(element).validate({
                errorContainer: obj.options.errorContainer,
                errorPlacement: function(error, element) {
                    error.appendTo(element.closest('.form-field').find('.inline-error'));
                    element.closest('.form-field').find('.inline-error').show();
                },
                invalidHandler: function(event, validator) {

                    jQuery(element).find('.validation-summary').find('p').html('Your form contains ' + validator.numberOfInvalids() + ' errors, see details below.');
                }
            });
        }
    };

    $.fn.synthValidation = function(options) {

        return this.each(function() {
            new jQuery.synthValidation(this, options);
        });
    }
})(jQuery, window, document);

jQuery(document).ready(function()
{
    jQuery('#syn_restaurant_manager_reservation_form').synthValidation();

    jQuery('#post').validate();
});


