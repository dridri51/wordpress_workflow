/* 
 * SyntaxThemes Controls Backend
 * 
 * @author Ryan Haworth
 */
(function(context, window, document, undefined) {

    context.SynthControls = function(options) {

        this.initialize();
    };

    context.SynthControls.prototype = {
        initialize: function() {

            this.color_picker_initialize();
        },
        color_picker_initialize: function() {
            /**
             * Initialize color picker
             */
            if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {

                jQuery('input:text.synth-colorpicker').wpColorPicker();

            } else {
                jQuery('input:text.synth-colorpicker').each(function(i) {

                    jQuery(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
                    jQuery('#picker-' + i).hide().farbtastic(jQuery(this));

                }).focus(function() {
                    jQuery(this).next().show();
                }).blur(function() {
                    jQuery(this).next().hide();
                });
            }
        }
    };
})(syn_restaurant_manager_js, window, document);

jQuery.fn.getType = function() {
    return this[0].tagName === "INPUT" ? jQuery(this[0]).attr("type").toLowerCase() : this[0].tagName.toLowerCase();
};