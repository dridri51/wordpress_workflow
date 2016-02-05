jQuery(document).ready(function() {

    (function() {

        var button_name = 'shortcodes_button';
        var plugin_name = theme_shortcodes.globals[button_name]['plugin_name'];
        var editor;

        tinymce.create('tinymce.plugins.' + plugin_name, {
            /**
             * Initializes the plugin, this will be executed after the plugin has been created.
             * This call is done before the editor instance has finished it's initialization so use the onInit event
             * of the editor instance to intercept that event.
             *
             * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
             * @param {string} url Absolute URL to where the plugin is located.
             */
            init: function(ed, url) {

                editor = ed;
                editor.addCommand("opensynthModal", function(ui, params) {

                    var modal = new jQuery.synthModal(params);
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
            createControl: function(n, cm) {

                var plugin = this;
                var control = n;
                var control_manager = cm;

                if (control !== button_name)
                    return null;

                var button = control_manager.createMenuButton(control, {
                    icons: false,
                    title: theme_shortcodes.globals[control].title
                });

                button.onRenderMenu.add(function(button, menu) {

                    menu.add({
                        title: 'Shortcodes',
                        class: 'mceMenuTitle'
                    }).setDisabled(1);

                    var shortcodes = theme_shortcodes.globals[control].config;
                    var submenu = [];

                    for (var i in shortcodes) {

                        submenu[shortcodes[i].menu] = [];
                    }

                    for (var title in submenu) {

                        if (title !== 'undefined') {

                            submenu[title] = menu.addMenu({
                                title: title
                            });
                        }
                    }
                    menu.addSeparator();

                    for (var j in shortcodes) {

                        if (shortcodes[j].show_in_menu !== false) {
                            var current_menu = shortcodes[j].menu ? submenu[shortcodes[j].menu] : menu;
                            if (shortcodes[j].modal_editor === false)
                            {
                                plugin.directToEditorWrite(current_menu, shortcodes[j]);
                            }
                            else
                            {
                                plugin.modalToEditorWrite(current_menu, shortcodes[j]);
                            }
                        }
                    }
                });
                return button;
            },
            directToEditorWrite: function(menu, shortcode) {

                menu.add({
                    title: shortcode.heading,
                    onclick: function()
                    {
                        tinymce.activeEditor.execCommand("mceInsertContent", false, window.switchEditors.wpautop(shortcode.editor_insert));
                    }
                });
            },
            modalToEditorWrite: function(menu, shortcode) {

                var plugin = this;

                menu.add({
                    title: shortcode.heading,
                    onclick: function()
                    {
                        tinymce.activeEditor.execCommand("opensynthModal", false,
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
        tinymce.PluginManager.add(plugin_name, tinymce.plugins.synth_shortcode_plugin);
    })();
});


