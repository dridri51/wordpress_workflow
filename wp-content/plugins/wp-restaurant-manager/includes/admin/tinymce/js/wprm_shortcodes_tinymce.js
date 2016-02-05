(function() {
	tinymce.PluginManager.add( 'wprm_shortcodes_mce_button', function( editor, url ) {
		editor.addButton( 'wprm_shortcodes_mce_button', {
			title: 'WPRM Shortcodes',
			type: 'menubutton',
			icon: 'icon wprm-shortcodes-icon',
			menu: [

				/* Booking Form */
				{
					text: 'Booking Form',
					onclick: function() {
						editor.insertContent( '[wprm_booking_form]');
					}
				}, // Booking Form

				/* Single Menu Item */
				{
					text: 'Single Menu Item',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Single Menu Item',
							body: [ 
								
								{
									type: 'textbox', 
									name: 'item_id', 
									label: 'Item ID',
									value: ''
								},
								{
									type: 'listbox',
									name: 'hyperlink',
									label: 'Link to single item?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'description',
									label: 'Display Description?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'price',
									label: 'Display Price?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'display_images',
									label: 'Display Images?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								}, 
							],
							onsubmit: function( e ) {
								editor.insertContent( '[wprm_single_item id="' + e.data.item_id + '" hyperlink="' + e.data.hyperlink + '" description="' + e.data.description + '" price="' + e.data.price + '" display_images="' + e.data.display_images + '"]');
							}
						});
					}
				}, // End Single Menu Item

				/* Menu By Category */
				{
					text: 'Menu By Category',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Menu By Category',
							body: [ 
								
								{
									type: 'textbox', 
									name: 'category_slug', 
									label: 'Menu Category Slug',
									value: ''
								},
								{
									type: 'listbox',
									name: 'category_title',
									label: 'Display Category Title?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'category_description',
									label: 'Display Category Description?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'hyperlink',
									label: 'Link to single item page?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'description',
									label: 'Display Description of single menu item?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'price',
									label: 'Display Price of single menu item?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'display_images',
									label: 'Display Images?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								}, 
							],
							onsubmit: function( e ) {
								editor.insertContent( '[wprm_menu_category category_slug="' + e.data.category_slug + '" category_title="' + e.data.category_title + '" category_description="' + e.data.category_description + '" hyperlink="' + e.data.hyperlink + '" description="' + e.data.description + '" price="' + e.data.price + '" display_images="' + e.data.display_images + '"]');
							}
						});
					}
				}, // End Menu By Category

				/* Menu */
				{
					text: 'Full Menu',
					onclick: function() {
						editor.windowManager.open( {
							title: 'Full Menu',
							body: [ 
								
								{
									type: 'listbox',
									name: 'category_title',
									label: 'Display Category Title?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'category_description',
									label: 'Display Category Description?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'hyperlink',
									label: 'Link to single item page?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'description',
									label: 'Display Description of single menu item?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
								{
									type: 'listbox',
									name: 'price',
									label: 'Display Price of single menu item?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								}, 
								{
									type: 'listbox',
									name: 'display_images',
									label: 'Display Images?',
									'values': [
										{text: 'Yes', value: 'true'},
										{text: 'No', value: 'false'}
									]
								},
							],
							onsubmit: function( e ) {
								editor.insertContent( '[wprm_menu category_title="' + e.data.category_title + '" category_description="' + e.data.category_description + '" hyperlink="' + e.data.hyperlink + '" description="' + e.data.description + '" price="' + e.data.price + '" display_images="' + e.data.display_images + '"]');
							}
						});
					}
				}, // End Menu
				

			]
		});
	});
})();