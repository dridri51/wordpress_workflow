"use strict";

jQuery(document).ready(function()
{
    jQuery(document.body).on('change', '#syn_restaurant_menu_menu_id', syn_restaurant_menus_get_meal_items);
    jQuery(document.body).on('change', '#syn_restaurant_menu_course_id', syn_restaurant_menus_get_meal_items);
});

function syn_restaurant_menus_get_meal_items() {

    var menu_id = jQuery('#syn_restaurant_menu_menu_id').val();
    var course_id = jQuery('#syn_restaurant_menu_course_id').val();

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        dataType: 'xml',
        data: {
            action: 'restaurant_manager_get_meal_options',
            menu_id: menu_id,
            course_id: course_id
        },
        success: function(response) {

            var html = jQuery(response).find('response_data').text();
            jQuery('#syn_restaurant_menu_ids').html(html);
        },
        error: function(response, jqXHR, textStatus, errorThrown) {

            alert(textStatus, errorThrown);
        },
        complete: function(response) {
        }
    });

}