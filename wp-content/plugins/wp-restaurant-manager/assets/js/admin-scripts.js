/**
 * Frontend Scripts
 * WP Restaurant Manager Plugin
 *
 * @package     wprm
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @copyright   (c) Pippin Williamson for the repeatable table row taken from EDD.
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/
jQuery(document).ready(function ($) {

	// Date picker
	if ( $( '#wprm_dates_to_exclude .regular-text' ).length > 0 ) {
		var dateFormat = 'MM dd, yy';

		$("#wprm_dates_to_exclude .regular-text").datepicker({
			dateFormat: dateFormat
		});
	}

	/**
	 * Settings screen JS
	 */
	var WPRM_Settings = {

		init : function() {
			this.party();
			this.dates();
		},

		party : function() {

			// Insert new table row
			$('#wprm_add_party_size').on('click', function() {
				var row = $('#wprm_party_sizes tr:last');
				var clone = row.clone();
				var count = row.parent().find( 'tr' ).length;
				clone.find( 'td input' ).val( '' );
				clone.find( 'input, select' ).each(function() {
					var name = $( this ).attr( 'name' );
					name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');
					$( this ).attr( 'name', name ).attr( 'id', name );
				});
				clone.insertAfter( row );
				return false;
			});

			// Remove table row
			$('body').on('click', '#wprm_party_sizes .wprm_remove_party_size', function() {
				if( confirm( wprm_admin_js_settings.delete_table ) )
					$(this).closest('tr').remove();
				return false;
			});

		},

		dates : function() {

			// Insert new table row
			$('#wprm_add_date_to_exclude').on('click', function() {
				var row = $('#wprm_dates_to_exclude tr:last');
				var clone = row.clone();
				var count = row.parent().find( 'tr' ).length;
				clone.find( 'td input' ).val( '' );
				clone.find( 'input, select' ).each(function() {
					var name = $( this ).attr( 'name' );
					name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');
					$( this ).attr( 'name', name ).attr( 'id', name );
					$( this ).removeClass("hasDatepicker");
			       	$( this ).datepicker({
			       		dateFormat: 'MM dd, yy'
			       	});
				});
				clone.insertAfter( row );
				return false;
			});

			// Remove table row
			$('body').on('click', '#wprm_dates_to_exclude .wprm_remove_date_to_exclude', function() {
				if( confirm( wprm_admin_js_settings.delete_date ) )
					$(this).closest('tr').remove();
				return false;
			});

		},

	}
	WPRM_Settings.init();

});
