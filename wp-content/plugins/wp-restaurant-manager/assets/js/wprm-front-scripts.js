/**
 * Frontend Scripts
 * WP Restaurant Manager Plugin
 *
 * @package     wprm
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/
jQuery(document).ready(function ($) {

	/**
	 * Frontend Scripts
	 */
	var WPRM_Settings = {

		init : function() {
			this.datepicker();
		},

		datepicker : function() {

			// Date picker
			if ( $( '#wprm-booking-form #date' ).length > 0 ) {
				var wprm_date_picker = $('#wprm-booking-form #date').pickadate({
					format: wprm_frontend_js_settings.date_format,
					formatSubmit: 'yyyy-m-d',
					hiddenPrefix: 'wprm__',
    				hiddenSuffix: '__hidden',
					min: true,
				    max: false,
				});

				// Get dates from json string.
				var json_dates = wprm_frontend_js_settings.disabled_dates;

				// Get the datepicker
				var get_the_datepicker = wprm_date_picker.pickadate( 'picker' );

				// Set dates to the datepicker
				$.each(json_dates, function (key, data) {
				    $.each(data, function (index, data) {
				        get_the_datepicker.set('disable', [new Date(data)]);
				    })
				})
				

			}
			// Time picker
			if ( $( '#wprm-booking-form #time' ).length > 0 ) {
				var wprm_time_picker = $('#wprm-booking-form #time').pickatime({
					format: wprm_frontend_js_settings.time_format,
					formatSubmit: 'HH:i',
					interval: parseInt(wprm_frontend_js_settings.time_interval, 10),
					min: wprm_frontend_js_settings.opening_time,
					max: wprm_frontend_js_settings.closing_time,
					hiddenPrefix: 'wprm__',
    				hiddenSuffix: '__hidden',
				});

			}

		},

	}
	WPRM_Settings.init();

});
