/* 
 * SyntaxThemes Modal
 * 
 * @author Ryan Haworth
 */
(function(context, window, document, undefined) {
    
    "use strict";

    context.SynthModal = function(options) {

        var obj = this;
        var defaults = {
            scope: this,
            modal_title: '',
            modal_class: '',
            modal_style: '',
            modal_bg_style: '',
            modal_content: '',
            modal_template: '',
            ajax_hook: '',
            nested_modal: null,
            save_callback: function() {
            },
            load_callback: function() {
            },
            close_callback: function() {
            },
        };
        obj.options = jQuery.extend({}, defaults, options);
        obj.modal = jQuery('<div class="synth-modal' + obj.options.modal_class + '" ' + obj.options.modal_style + '></div>');
        obj.background = jQuery('<div class="synth-modal-background" ' + obj.options.modal_bg_style + '></div>');
        obj.body = jQuery('body');
        obj.initialize();
    };
    context.SynthModal.prototype = {
        initialize: function() {

            var obj = this;
            obj.create_modal();
            obj.bind_events();
        },
        create_modal: function() {

            var obj = this;
            var content = this.options.modal_content ? obj.options.modal_content : '';
            var title = '<h4 class="synth-modal-title">' + obj.options.modal_title + '</h4>';
            var loading = '';
            var modal_html = '<div class="synth-modal-content">';
            modal_html += '<div class="synth-modal-content-header"><span class="synth-modal-icon rman-syntaxstudio"></span>' + title + '<a href="#close" class="synth-modal-close-event close-button"></a></div>';
            modal_html += '<div class="synth-modal-inner-content ' + loading + '">' + content + '</div>';
            modal_html += '<div class="synth-modal-inner-footer">';

            if (obj.options.modal_button === "save") {

                modal_html += '<a href="#save" class="synth-modal-save-event modal-button button-primary">Save</a>';
            }
            else if (obj.options.modal_button === "close")
            {
                modal_html += '<a href="#close" class="synth-modal-close-event modal-button button-primary">Close</a>';
            }

            modal_html += '</div></div>';
            obj.body.append(obj.modal).append(obj.background);
            obj.modal.html(modal_html);

            if (!obj.options.modal_content)
            {
                obj.call_ajax_content();
            }
            else
            {
                obj.execute_load_callback();
            }

        },
        bind_events: function() {


            var obj = this;
            obj.modal.on('click', '.synth-modal-save-event', function(e) {

                obj.save();
                e.preventDefault();
            });
            obj.modal.on('click', '.synth-modal-close-event', function(e) {

                obj.close();
                e.preventDefault();
            });
            obj.background.add('synth-modal-close-event', this.modal).on('click', function(e) {

                obj.close();
                e.preventDefault();
            });
        },
        loaded: function() {

            var obj = this;
            obj.execute_load_callback();
            syn_restaurant_manager_js_shortcodes.backend_modal_loaded();
        },
        save: function() {

            var obj = this;
            obj.execute_save_callback();
        },
        close: function() {

            var obj = this;
            obj.modal.remove();
            obj.background.remove();

            obj.options['close_callback'].call();
        },
        execute_load_callback: function() {

            var obj = this;
            var callback_supports = this.options.load_callback;
            var callback;
            var index = 0;
            if (callback_supports instanceof Array) {

                for (index in callback_supports) {
                    context.SynthModal.callback_support[callback_supports[index]].call(this);
                }
            }
            if (typeof callback_supports === 'string') {

                callback = callback_supports.split(",");
                if (index in callback) {
                    context.SynthModal.callback_support[callback[index]].call(this);
                }
            }
            else if (typeof callback_supports === 'function')
            {
                callback_supports.call();
            }
        },
        execute_save_callback: function() {

            var obj = this;
            var elements = {};
            var values = obj.modal.find('input, select, radio, textarea').not('[data-visibility="hidden"]').serializeArray();
            var value_array = obj.convert_values(values);
            var close = obj.options['save_callback'].call(obj.options.scope, value_array, obj.options.save_param);

            if (close !== false)
            {
                obj.close();
            }
        },
        call_ajax_content: function() {

            var obj = this;
            var inner_modal = obj.modal.find('.synth-modal-inner-content');

            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'xml',
                data: {
                    action: obj.options.ajax_hook,
                    params: obj.options.modal_template
                },
                success: function(response) {

                    var html = jQuery(response).find('response_data').text();
                    inner_modal.html(html);
                    //modal has loaded the html
                    obj.loaded();
                },
                error: function(response, jqXHR, textStatus, errorThrown) {

                    var html = jQuery(response).find('response_data').text();
                    jQuery('.wrap h2').after(html);
                    alert(textStatus, errorThrown);
                },
                complete: function(response) {
                }
            });
        },
        convert_values: function(value)
        {
            var value_array = {};
            jQuery.each(value, function()
            {
                if (typeof value_array[this.name] !== 'undefined')
                {
                    if (!value_array[this.name].push)
                    {
                        value_array[this.name] = [value_array[this.name]];
                    }
                    value_array[this.name].push(this.value || '');
                }
                else
                {
                    value_array[this.name] = this.value || '';
                }
            });
            return value_array;
        }
    };
    context.SynthModal.callback_support = context.SynthModal.callback_support || {};
    context.SynthModal.callback_support.colorpicker = function() {

        syn_restaurant_manager_js_controls.color_picker_initialize();
    };
    context.SynthModal.callback_support.sortables = function() {
        modal_support_sortables();
    };
    context.SynthModal.callback_support.tinymce = function() {
        modal_support_tinymce(this);
    };
    context.SynthModal.callback_support.nested_element_builder = function() {

        syn_restaurant_manager_js_controls.nested_shortcode_control_initialize();
    };
    context.SynthModal.callback_support.table_builder = function() {

        syn_restaurant_manager_js_controls.table_builder_initialize();
        //syn_restaurant_manager_js_controls.shortcode_launcher_initialize();
        //jQuery('.shortcode-launch-button').ShortcodeLauncher();
    };
    context.SynthModal.callback_support.tabs_builder = function() {

        syn_restaurant_manager_js_controls.tabs_builder_initialize();
    };
})(syn_restaurant_manager_js, window, document);

function modal_support_sortables() {

    jQuery('.sortables').sortable({
        handle: 'span'
    });
}

function modal_support_tinymce(obj) {

    var editors = obj.modal.find('.synth_tinymce');
    var save_btn = obj.modal.find('.synth-modal-save-event');
    editors.each(function()
    {
        var el_id = this.id;
        var current = jQuery(this);
        var parent = current.parents('.wp-editor-wrap:eq(0)');
        var textarea = parent.find('textarea.synth_tinymce');
        var switch_btn = parent.find('.wp-switch-editor').removeAttr("onclick");
        var settings = {
            id: this.id,
            buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close"
        };

        tinyVersion = window.tinyMCE.majorVersion,
                executeAdd = "mceAddControl",
                executeRem = "mceRemoveControl";

        if (tinyVersion >= 4)
        {
            executeAdd = "mceAddEditor";
            executeRem = "mceRemoveEditor";
        }

        quicktags(settings);
        QTags._buttonsInit();

        // modify behavior for html editor
        switch_btn.bind('click', function()
        {
            var button = jQuery(this);
            if (button.is('.switch-tmce'))
            {
                parent.removeClass('html-active').addClass('tmce-active');
                window.tinyMCE.execCommand(executeAdd, true, el_id);
                window.tinyMCE.get(el_id).setContent(window.switchEditors.wpautop(textarea.val()), {
                    format: 'raw'
                });
            }
            else
            {
                parent.removeClass('tmce-active').addClass('html-active');
                window.tinyMCE.execCommand(executeRem, true, el_id);
            }
        }).trigger('click');
        //make sure that when the save button is pressed the textarea gets updated and sent to the editor
        save_btn.bind('click', function()
        {
            switch_btn.filter('.switch-html').trigger('click');
        });
    });
}


