"use strict";

// Global Namespace
var syn_restaurant_manager_js = {};

var syn_restaurant_manager_js_core = null;
var syn_restaurant_manager_js_controls = null;
var syn_restaurant_manager_js_shortcodes = null;

//@prepros-append synth-core.js
//@prepros-append synth-controls.js
//@prepros-append synth-shortcodes.js
//@prepros-append synth-modal.js

jQuery(document).ready(function()
{
    syn_restaurant_manager_js_core = new syn_restaurant_manager_js.SynthCore();
    syn_restaurant_manager_js_controls = new syn_restaurant_manager_js.SynthControls();
    syn_restaurant_manager_js_shortcodes = new syn_restaurant_manager_js.SynthShortcodes();
});