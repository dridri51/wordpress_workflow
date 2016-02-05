/* 
 * SyntaxThemes Core
 * 
 * @author Ryan Haworth
 */
(function(context, window, document, undefined) {

    "use strict";

    context.SynthCore = function(options) {

        this.initialize();
    };

    context.SynthCore.prototype = {
        initialize: function() {

            this.create_nested_shortcode_modal();
            this.template_sidebar_chooser();
        },
        create_nested_shortcode_modal: function() {

            jQuery(document.body).on('click', '.activate-nested-modal', function(event) {

                var control = jQuery(this).closest('.synth-control-root');
                var selected_item = jQuery(this);
                var parent_shortcode_name = jQuery(this).closest('ul').data('parent-shortcode');
                var config = synth_taurus_scodes.globals['synth_taurus_shortcodes_button'].config;
                var parent_shortcode = config[parent_shortcode_name];
                var nested_shortcode = config[parent_shortcode.nested_shortcode];
                var template = selected_item.find('textarea').html();
                var template2 = control.find('.item-template').html();
                var data = control.find('.item-template').data('parts');

                var params = {
                    scope: this,
                    modal_title: nested_shortcode.heading,
                    modal_style: 'style="z-index: 10003; margin: 50px;"',
                    modal_button: nested_shortcode.modal_button,
                    modal_bg_style: 'style="z-index: 10002"',
                    modal_template: template,
                    ajax_hook: nested_shortcode.name,
                    load_callback: nested_shortcode.modal_support,
                    save_callback: function(values) {

                        var params = [];
                        var shortcode;
                        var merged_template;

                        params = syn_restaurant_manager_js_core.extract_shortcode_params(nested_shortcode.name, values);
                        merged_template = syn_restaurant_manager_js_core.merge_template(template2, data, params);
                        shortcode = syn_restaurant_manager_js_core.create_shortcode_tag(config[parent_shortcode_name].nested_shortcode, params);

                        //place the output into the text area.
                        selected_item.find('textarea').html(shortcode);
                        selected_item.find('.template').html(merged_template);
                    }
                };

                new jQuery.synthModal(params);
            });
        },
        create_shortcode_tag: function(shortcode, params) {

            var tag = {};
            var attributes = '';
            var content = '';
            var output = '';
            var seperator = ' ';

            //get the content
            if (typeof params.content !== 'undefined') {

                if (typeof params.content === 'object') {

                    if (jQuery.isArray(params.content)) {
                        seperator = '\n';
                    }

                    for (var i = 0; i < params.content.length; i++)
                    {
                        params.content[i] = jQuery.trim(params.content[i]);
                    }
                    content += params.content.join(seperator);
                }
                else {
                    content = jQuery.trim(params.content);
                }
                delete params.content;
            }

            if (typeof params.editor !== 'undefined') {

                if (typeof params.editor === 'string') {

                    content += jQuery.trim(params.editor);
                }
                delete params.editor;
            }

            for (var key in params) {

                if (params.hasOwnProperty(key)) {
                    if (params[key] !== "") {
                        attributes += key + '="' + params[key] + '" ';
                    }
                }
            }

            tag.start = '[' + shortcode + ((attributes !== '') ? ' ' + jQuery.trim(attributes) : '');
            tag.close = ']';

            if (content) {
                tag.start += ']';
                tag.close = '[/' + shortcode + ']';
            }

            output = tag.start + content + tag.close;

            return output;
        },
        extract_shortcode_params: function(shortcode_name, values) {

            var params = [];

            var prefix = shortcode_name + '_';

            for (var val in values) {

                var name = val.replace(prefix, "");
                if (name !== val && name !== prefix) {

                    params[name] = values[val];
                    delete values[val];
                }
            }
            values = null;

            return params;
        },
        merge_template: function(template, params, values) {

            for (var key in params) {
                var part = params[key];
                template = template.replace('%' + part + '%', values[part]);
            }

            return template;

        },
        template_sidebar_chooser: function() {

            var selected = jQuery('#page_sidebar_layout_selector').val();
            on_page_sidebar_configuration_update(selected);
            on_page_template_changed();

            function on_page_template_changed() {

                jQuery('#page_sidebar_layout_selector').on('change', function() {

                    var selected = jQuery(this).val();
                    on_page_sidebar_configuration_update(selected);
                });
            }

            function on_page_sidebar_configuration_update(selected) {

                jQuery('#primary_widget_area_sidebar').hide();
                jQuery('#secondary_widget_area_sidebar').hide();
                jQuery('#empty_widget_area_sidebar').hide();

                if (selected === 'page-templates/full-width-content.php') {
                    jQuery('#empty_widget_area_sidebar').show();
                }
                if ((selected === 'one_left_sidebar') || (selected === 'one_right_sidebar')) {
                    jQuery('#primary_widget_area_sidebar').show();
                }
                if ((selected === 'two_left_sidebars') || (selected === 'two_right_sidebars') || (selected === 'left_right_sidebars') || (selected === 'right_left_sidebars')) {
                    jQuery('#primary_widget_area_sidebar').show();
                    jQuery('#secondary_widget_area_sidebar').show();
                }
            }
        }
    };

})(syn_restaurant_manager_js, window, document);