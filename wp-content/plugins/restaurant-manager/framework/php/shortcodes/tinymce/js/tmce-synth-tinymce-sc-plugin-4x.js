jQuery(document).ready(function() {

    "use strict";

    (function() {

        var button_name = 'restaurant_shortcodes';
        var plugin_name = syn_restaurant_manager_shortcodes.globals[button_name]['plugin_name'];

        tinymce.create('tinymce.plugins.' + plugin_name, {
            /**
             * Initializes the plugin, this will be executed after the plugin has been created.
             * This call is done before the editor instance has finished it's initialization so use the onInit event
             * of the editor instance to intercept that event.
             *
             * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
             * @param {string} url Absolute URL to where the plugin is located.
             */
            init: function(editor, url) {

                editor.addButton(button_name, {
                    type: 'menuButton',
                    text: '',
                    title: syn_restaurant_manager_shortcodes.globals[button_name].title,
                    image: syn_restaurant_manager_shortcodes.globals[button_name].image,
                    icons: button_name,
                    menu: this.createMenu(editor)
                });

                editor.addCommand('openSynthModal' + button_name, function(ui, params) {

                    var modal = new syn_restaurant_manager_js.SynthModal(params);
                    return false;
                });
            },
            /**
             * Creates control instances based in the incomming name. This method is normally not
             * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
             * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
             * method can be used to create those.
             *
             * @param {String} n Name of the control to create.
             * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
             * @return {tinymce.ui.Control} New control instance or null if no control was created.
             */
            createMenu: function(editor) {

                var menu_options = [];
                var shortcodes = syn_restaurant_manager_shortcodes.globals[button_name].config;
                var submenu = [];

                for (var i in shortcodes) {

                    submenu[shortcodes[i].menu] = [];
                }

                for (var title in submenu) {

                    if (title !== 'undefined') {

                        menu_options.push({text: title, menu: []});
                    }
                }

                for (var j in shortcodes) {

                    if (shortcodes[j].show_in_menu !== false) {
                        var current_menu = menu_options;

                        for (var title in menu_options) {

                            if (title !== 'undefined')
                            {
                                if (menu_options[title].text === shortcodes[j].menu) {
                                    current_menu = menu_options[title].menu;
                                }
                            }
                        }

                        var click_event = (shortcodes[j].modal_editor === false) ? this.directToEditorWrite : this.modalToEditorWrite;
                        current_menu.push({text: shortcodes[j].heading, onclick: click_event, shortcode: shortcodes[j]});
                    }
                }

                return menu_options;

            },
            directToEditorWrite: function() {

                var shortcode = this.settings.shortcode;
                tinymce.activeEditor.execCommand("mceInsertContent", false, window.switchEditors.wpautop(shortcode.editor_insert));
            },
            modalToEditorWrite: function() {

                var shortcode = this.settings.shortcode;
                tinymce.activeEditor.execCommand('openSynthModal' + button_name, false,
                        {
                            scope: tinymce.activeEditor,
                            modal_class: shortcode.modal_class,
                            modal_title: shortcode.heading,
                            modal_button: shortcode.modal_button,
                            ajax_hook: shortcode.name,
                            load_callback: shortcode.modal_support,
                            save_callback: function(values) {

                                var params = [];
                                var output;

                                var prefix = shortcode.name + '_';

                                for (var val in values) {

                                    var name = val.replace(prefix, "");
                                    if (name !== val && name !== prefix) {

                                        params[name] = values[val];
                                        delete values[val];
                                    }
                                }
                                values = null;

                                output = window.switchEditors.wpautop(syn_restaurant_manager_js_core.create_shortcode_tag(shortcode.name, params));
                                tinymce.activeEditor.execCommand("mceInsertContent", false, output);
                            }
                        });
            },
            /**
             * Returns information about the plugin as a name/value array.
             * The current keys are longname, author, authorurl, infourl and version.
             *
             * @return {Object} Name/value array containing information about the plugin.
             */
            getInfo: function() {
                return {
                    longname: 'Shortcodes Manager',
                    author: 'Ryan Haworth',
                    authorurl: 'http://www.syntaxthemes.co.uk',
                    version: "1.0"
                };
            }
        });
        // Register plugin
        tinymce.PluginManager.add(plugin_name, tinymce.plugins.syn_restaurant_manager_shortcodes_plugin);
    })();
});


