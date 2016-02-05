<?php
/**
 * Misc Functions
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get Currencies
 *
 * @since 1.0
 * @return array $currencies A list of the available currencies
 */
function wprm_get_currencies() {
	$currencies = array(
		'USD'  => __( 'US Dollars (&#36;)', 'wprm' ),
		'EUR'  => __( 'Euros (&euro;)', 'wprm' ),
		'GBP'  => __( 'Pounds Sterling (&pound;)', 'wprm' ),
		'AUD'  => __( 'Australian Dollars (&#36;)', 'wprm' ),
		'BRL'  => __( 'Brazilian Real (R&#36;)', 'wprm' ),
		'CAD'  => __( 'Canadian Dollars (&#36;)', 'wprm' ),
		'CZK'  => __( 'Czech Koruna', 'wprm' ),
		'DKK'  => __( 'Danish Krone', 'wprm' ),
		'HKD'  => __( 'Hong Kong Dollar (&#36;)', 'wprm' ),
		'HUF'  => __( 'Hungarian Forint', 'wprm' ),
		'ILS'  => __( 'Israeli Shekel (&#8362;)', 'wprm' ),
		'JPY'  => __( 'Japanese Yen (&yen;)', 'wprm' ),
		'MYR'  => __( 'Malaysian Ringgits', 'wprm' ),
		'MXN'  => __( 'Mexican Peso (&#36;)', 'wprm' ),
		'NZD'  => __( 'New Zealand Dollar (&#36;)', 'wprm' ),
		'NOK'  => __( 'Norwegian Krone', 'wprm' ),
		'PHP'  => __( 'Philippine Pesos', 'wprm' ),
		'PLN'  => __( 'Polish Zloty', 'wprm' ),
		'SGD'  => __( 'Singapore Dollar (&#36;)', 'wprm' ),
		'SEK'  => __( 'Swedish Krona', 'wprm' ),
		'CHF'  => __( 'Swiss Franc', 'wprm' ),
		'TWD'  => __( 'Taiwan New Dollars', 'wprm' ),
		'THB'  => __( 'Thai Baht (&#3647;)', 'wprm' ),
		'INR'  => __( 'Indian Rupee (&#8377;)', 'wprm' ),
		'TRY'  => __( 'Turkish Lira (&#8378;)', 'wprm' ),
		'RIAL' => __( 'Iranian Rial (&#65020;)', 'wprm' ),
		'RUB'  => __( 'Russian Rubles', 'wprm' )
	);

	return apply_filters( 'wprm_currencies', $currencies );
}

/**
 * Get Spicy Levels
 *
 * @since 1.0
 * @return array $spicy_levels A list of the available spicy levels
 */
function wprm_get_spicy_levels() {
	$spicy_levels = array(
		'0'  => __( '0', 'wprm' ),
		'1'  => __( '1', 'wprm' ),
		'2'  => __( '2', 'wprm' ),
		'3'  => __( '3', 'wprm' ),
		'4'  => __( '4', 'wprm' ),
		'5'  => __( '5', 'wprm' ),
	);

	return apply_filters( 'wprm_get_spicy_levels', $spicy_levels );
}

/**
 * Get Calendar Languages
 *
 * @since 1.0
 * @return array $calendar_languages A list of the available languages
 */
function wprm_get_calendar_languages() {

	$calendar_languages = array(
		''	=> __('Select Language','wprm'),
		'ar'	=> 'ar',
		'bg_BG'	=> 'bg_BG',
		'bs_BA'	=> 'bs_BA',
		'ca_ES'	=> 'ca_ES',
		'cs_CZ'	=> 'cs_CZ',
		'da_DK'	=> 'da_DK',
		'de_DE'	=> 'de_DE',
		'el_GR'	=> 'el_GR',
		'es_ES'	=> 'es_ES',
		'et_EE'	=> 'et_EE',
		'en_EN'	=> 'en_EN',
		'eu_ES'	=> 'eu_ES',
		'fi_FI'	=> 'fi_FI',
		'fr_FR'	=> 'fr_FR',
		'gl_ES'	=> 'gl_ES',
		'he_IL'	=> 'he_IL',
		'hr_HR'	=> 'hr_HR',
		'hu_HU'	=> 'hu_HU',
		'id_ID'	=> 'id_ID',
		'is_IS'	=> 'is_IS',
		'it_IT'	=> 'it_IT',
		'ja_JP'	=> 'ja_JP',
		'ko_KR'	=> 'ko_KR',
		'nl_NL'	=> 'nl_NL',
		'no_NO'	=> 'no_NO',
		'pl_PL'	=> 'pl_PL',
		'pt_BR'	=> 'pt_BR',
		'pt_PT'	=> 'pt_PT',
		'ro_RO'	=> 'ro_RO',
		'ru_RU'	=> 'ru_RU',
		'sk_SK'	=> 'sk_SK',
		'sl_SI'	=> 'sl_SI',
		'sv_SE'	=> 'sv_SE',
		'th_TH'	=> 'th_TH',
		'tr_TR'	=> 'tr_TR',
		'uk_UA'	=> 'uk_UA',
		'zh_CN'	=> 'zh_CN',
		'zh_TW'	=> 'zh_TW',
	);

	return apply_filters( 'wprm_get_calendar_languages', $calendar_languages );

}

/**
 * Get Early Bookings
 *
 * @since 1.0
 * @return array $early_bookings list of the available time slots
 */
function wprm_get_early_bookings() {
	$early_bookings = array(
		''  	=> __( 'Any Time', 'wprm' ),
		'1' 	=> __( 'Up to 1 day in advance', 'wprm' ),
		'7' 	=> __( 'Up to 1 week in advance', 'wprm' ),
		'14' 	=> __( 'Up to 2 weeks in advance', 'wprm' ),
		'30' 	=> __( 'Up to 30 days in advance', 'wprm' ),
		'90' 	=> __( 'Up to 90 days in advance', 'wprm' ),
	);

	return apply_filters( 'wprm_get_early_bookings', $early_bookings );
}

/**
 * Get Late Bookings
 *
 * @since 1.0
 * @return array $late_bookings list of the available time slots
 */
function wprm_get_late_bookings() {
	$late_bookings = array(
		'' 		=> __( 'Up to the last minute', 'wprm' ),
		'15' 	=> __( 'At least 15 minutes in advance', 'wprm' ),
		'30' 	=> __( 'At least 30 minutes in advance', 'wprm' ),
		'45' 	=> __( 'At least 45 minutes in advance', 'wprm' ),
		'60' 	=> __( 'At least 1 hour in advance', 'wprm' ),
		'240' 	=> __( 'At least 4 hours in advance', 'wprm' ),
	);

	return apply_filters( 'wprm_get_late_bookings', $late_bookings );
}

/**
 * Get Time Interval
 *
 * @since 1.0
 * @return array $time_interval list of the available time slots
 */
function wprm_get_time_interval() {
	$time_interval = array(
		'' 			=> __( 'Every 30 minutes', 'wprm' ),
		'60' 		=> __( 'Every 60 minutes', 'wprm' ),
		'45' 		=> __( 'Every 45 minutes', 'wprm' ),
		'15' 		=> __( 'Every 15 minutes', 'wprm' ),
		'10' 		=> __( 'Every 10 minutes', 'wprm' ),
		'5' 		=> __( 'Every 5 minutes', 'wprm' ),
	);

	return apply_filters( 'wprm_get_time_interval', $time_interval );
}

/**
 * Retrieve Party sizes
 *
 * @since 1.0.0
 * @global $wprm_options
 * @return array Defined party sizes
 */
function wprm_get_party_sizes() {

	$sizes = get_option( 'wprm_party_sizes', array() );
	return apply_filters( 'wprm_get_party_sizes', $sizes );
}

/**
 * Retrieve Excluded Dates
 *
 * @since 1.0.0
 * @global $wprm_options
 * @return array Defined excluded dates
 */
function wprm_get_dates_to_exclude() {

	$dates = get_option( 'wprm_dates_to_exclude', array() );
	return apply_filters( 'wprm_get_dates_to_exclude', $dates );
}

/**
 *  Determines whether the current admin page is an  admin page.
 *  
 *  Only works after the `wp_loaded` hook, & most effective 
 *  starting on `admin_menu` hook.
 *  
 *  @since 1.0.0
 *  @return bool True if admin page.
 */
function wprm_is_admin_page() {

	if ( ! is_admin() || ! did_action( 'wp_loaded' ) ) {
		return false;
	}
	
	global $pagenow, $typenow, $wprm_settings_page;

	if ( 'download' == $typenow || 'index.php' == $pagenow || 'post-new.php' == $pagenow || 'post.php' == $pagenow || 'edit.php' == $pagenow ) {
		return true;
	}
	
	$wprm_admin_pages = apply_filters( 'wprm_admin_pages', array( $wprm_settings_page ) );
	
	if ( in_array( $pagenow, $wprm_admin_pages ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Verifies if reservation notifications are enabled.
 *
 * @since 1.0.0
 * @global $wprm_options
 * @return $message string containing a message.
 */
function wprm_reservations_single_notifications_enabled() {

	$message = null;

	if(isset($_GET['reservation']) && $_GET['reservation'] == 'approved' && wprm_get_option('mail_user_booking_success')) {
		$message = __('An email notification has been sent to the customer.','wprm');
	} else if(isset($_GET['reservation']) && $_GET['reservation'] == 'rejected' && wprm_get_option('mail_user_booking_rejected')) {
		$message = __('An email notification has been sent to the customer.','wprm');
	}

	return $message;

}

/**
 * Get Menu Styles
 *
 * @since 1.0
 * @return array $menu_styles list of the available menu styles
 */
function wprm_get_menu_styles() {
	$menu_styles = array(
		'minimal' => __( 'Minimal', 'wprm' )
	);

	return apply_filters( 'wprm_get_menu_styles', $menu_styles );
}

/**
 * Get Booking form Styles
 *
 * @since 1.0
 * @return array $booking_styles list of the available booking form picker styles
 */
function wprm_get_booking_styles() {
	$booking_styles = array(
		'default' => __( 'Default', 'wprm' ),
		'classic' => __( 'Classic', 'wprm' )
	);

	return apply_filters( 'wprm_get_booking_styles', $booking_styles );
}

/**
 * Get and include template files.
 *
 * @param mixed $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function get_wprm_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array($args) )
		extract( $args );

	include( locate_wprm_template( $template_name, $template_path, $default_path ) );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @param mixed $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function locate_wprm_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path )
		$template_path = 'wprm';
	if ( ! $default_path )
		$default_path = WPRM_PLUGIN_DIR . '/templates/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);

	// Get default template
	if ( ! $template )
		$template = $default_path . $template_name;

	// Return what we found
	return apply_filters( 'wprm_locate_template', $template, $template_name, $template_path );
}

/**
 * Get template part (for templates in loops).
 *
 * @param mixed $slug
 * @param string $name (default: '')
 * @return void
 */
function get_wprm_template_part( $slug, $name = '', $template_path = '', $default_path = '' ) {
	if ( ! $template_path )
		$template_path = 'wprm';
	if ( ! $default_path )
		$default_path = WPRM_PLUGIN_DIR . '/templates/';

	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/wprm/slug-name.php
	if ( $name )
		$template = locate_template( array ( "{$slug}-{$name}.php", "{$template_path}/{$slug}-{$name}.php" ) );

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( $default_path . "{$slug}-{$name}.php" ) )
		$template = $default_path . "{$slug}-{$name}.php";

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/wprm/slug.php
	if ( ! $template )
		$template = locate_template( array( "{$slug}.php", "{$template_path}/{$slug}.php" ) );

	if ( $template )
		load_template( $template, false );
}

/**
  *  Resizes an image and returns the resized URL. Uses native WordPress functionality.
  *
  *  The function supports GD Library and ImageMagick. WordPress will pick whichever is most appropriate.
  *  If none of the supported libraries are available, the function will return the original image url.
  *
  *  Images are saved to the WordPress uploads directory, just like images uploaded through the Media Library.
  * 
  *  Supports WordPress 3.5 and above.
  * 
  *  Based on resize.php by Matthew Ruddy (GPLv2 Licensed, Copyright (c) 2012, 2013)
  *  https://github.com/MatthewRuddy/Wordpress-Timthumb-alternative
  * 
  *  License: GPLv2
  *  http://www.gnu.org/licenses/gpl-2.0.html
  *
  *  @author Ernesto Méndez (http://der-design.com)
  *  @author Matthew Ruddy (http://rivaslider.com)
  */

add_action('delete_attachment', 'mr_delete_resized_images');

if(!function_exists('mr_image_resize')) :

	function mr_image_resize($url, $width=null, $height=null, $crop=true, $align='c', $retina=false) {

	  global $wpdb;

	  // Get common vars (func_get_args() only get specified values)
	  $common = mr_common_info($url, $width, $height, $crop, $align, $retina);
	  
	  // Unpack vars if got an array...
	  if (is_array($common)) extract($common);

	  // ... Otherwise, return error, null or image
	  else return $common;

	  if (!file_exists($dest_file_name)) {

	    // We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
	    $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE guid='%s'", $url);
	    $get_attachment = $wpdb->get_results($query);

	    // Load WordPress Image Editor
	    $editor = wp_get_image_editor($file_path);
	    
	    // Print possible wp error
	    if (is_wp_error($editor)) {
	      if (is_user_logged_in()) print_r($editor);
	      return null;
	    }

	    if ($crop) {

	      $src_x = $src_y = 0;
	      $src_w = $orig_width;
	      $src_h = $orig_height;

	      $cmp_x = $orig_width / $dest_width;
	      $cmp_y = $orig_height / $dest_height;

	      // Calculate x or y coordinate and width or height of source
	      if ($cmp_x > $cmp_y) {

	        $src_w = round ($orig_width / $cmp_x * $cmp_y);
	        $src_x = round (($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);

	      } else if ($cmp_y > $cmp_x) {

	        $src_h = round ($orig_height / $cmp_y * $cmp_x);
	        $src_y = round (($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);

	      }

	      // Positional cropping. Uses code from timthumb.php under the GPL
	      if ($align && $align != 'c') {
	        if (strpos ($align, 't') !== false) {
	          $src_y = 0;
	        }
	        if (strpos ($align, 'b') !== false) {
	          $src_y = $orig_height - $src_h;
	        }
	        if (strpos ($align, 'l') !== false) {
	          $src_x = 0;
	        }
	        if (strpos ($align, 'r') !== false) {
	          $src_x = $orig_width - $src_w;
	        }
	      }
	      
	      // Crop image
	      $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);

	    } else {
	     
	      // Just resize image
	      $editor->resize($dest_width, $dest_height);
	     
	    }

	    // Save image
	    $saved = $editor->save($dest_file_name);
	    
	    // Print possible out of memory error
	    if (is_wp_error($saved)) {
	      if (is_user_logged_in()) {
	        print_r($saved);
	        unlink($dest_file_name);
	      }
	      return null;
	    }

	    // Add the resized dimensions and alignment to original image metadata, so the images
	    // can be deleted when the original image is delete from the Media Library.
	    if ($get_attachment) {
	      $metadata = wp_get_attachment_metadata($get_attachment[0]->ID);
	      if (isset($metadata['image_meta'])) {
	        $md = $saved['width'] . 'x' . $saved['height'];
	        if ($crop) $md .= ($align) ? "_${align}" : "_c";
	        $metadata['image_meta']['resized_images'][] = $md;
	        wp_update_attachment_metadata($get_attachment[0]->ID, $metadata);
	      }
	    }

	    // Resized image url
	    $resized_url = str_replace(basename($url), basename($saved['path']), $url);

	  } else {

	    // Resized image url
	    $resized_url = str_replace(basename($url), basename($dest_file_name), $url);

	  }

	  // Return resized url
	  return $resized_url;

	}
endif;

// Returns common information shared by processing functions
if(!function_exists('mr_common_info')) :
	function mr_common_info($url, $width, $height, $crop, $align, $retina) {

	  // Return null if url empty
	  if (empty($url)) {
	    return is_user_logged_in() ? "image_not_specified" : null;
	  }

	  // Return if nocrop is set on query string
	  if (preg_match('/(\?|&)nocrop/', $url)) {
	    return $url;
	  }
	  
	  // Get the image file path
	  $urlinfo = parse_url($url);
	  $wp_upload_dir = wp_upload_dir();
	  
	  if (preg_match('/\/[0-9]{4}\/[0-9]{2}\/.+$/', $urlinfo['path'], $matches)) {
	    $file_path = $wp_upload_dir['basedir'] . $matches[0];
	  } else {
	    $pathinfo = parse_url( $url );
	    $uploads_dir = is_multisite() ? '/files/' : '/wp-content/';
	    $file_path = ABSPATH . str_replace(dirname($_SERVER['SCRIPT_NAME']) . '/', '', strstr($pathinfo['path'], $uploads_dir));
	    $file_path = preg_replace('/(\/\/)/', '/', $file_path);
	  }
	  
	  // Don't process a file that doesn't exist
	  if (!file_exists($file_path)) {
	    return null; // Degrade gracefully
	  }
	  
	  // Get original image size
	  $size = is_user_logged_in() ? getimagesize($file_path) : @getimagesize($file_path);

	  // If no size data obtained, return error or null
	  if (!$size) {
	    return is_user_logged_in() ? "getimagesize_error_common" : null;
	  }

	  // Set original width and height
	  list($orig_width, $orig_height, $orig_type) = $size;

	  // Generate width or height if not provided
	  if ($width && !$height) {
	    $height = floor ($orig_height * ($width / $orig_width));
	  } else if ($height && !$width) {
	    $width = floor ($orig_width * ($height / $orig_height));
	  } else if (!$width && !$height) {
	    return $url; // Return original url if no width/height provided
	  }

	  // Allow for different retina sizes
	  $retina = $retina ? ($retina === true ? 2 : $retina) : 1;

	  // Destination width and height variables
	  $dest_width = $width * $retina;
	  $dest_height = $height * $retina;

	  // Some additional info about the image
	  $info = pathinfo($file_path);
	  $dir = $info['dirname'];
	  $ext = $info['extension'];
	  $name = wp_basename($file_path, ".$ext");

	  // Suffix applied to filename
	  $suffix = "${dest_width}x${dest_height}";

	  // Set align info on file
	  if ($crop) {
	    $suffix .= ($align) ? "_${align}" : "_c";
	  }

	  // Get the destination file name
	  $dest_file_name = "${dir}/${name}-${suffix}.${ext}";
	  
	  // Return info
	  return array(
	    'dir' => $dir,
	    'name' => $name,
	    'ext' => $ext,
	    'suffix' => $suffix,
	    'orig_width' => $orig_width,
	    'orig_height' => $orig_height,
	    'orig_type' => $orig_type,
	    'dest_width' => $dest_width,
	    'dest_height' => $dest_height,
	    'file_path' => $file_path,
	    'dest_file_name' => $dest_file_name,
	  );

	}
endif;

// Deletes the resized images when the original image is deleted from the WordPress Media Library.
if(!function_exists('mr_delete_resized_images')) :
	function mr_delete_resized_images($post_id) {

	  // Get attachment image metadata
	  $metadata = wp_get_attachment_metadata($post_id);
	  
	  // Return if no metadata is found
	  if (!$metadata) return;

	  // Return if we don't have the proper metadata
	  if (!isset($metadata['file']) || !isset($metadata['image_meta']['resized_images'])) return;
	  
	  $wp_upload_dir = wp_upload_dir();
	  $pathinfo = pathinfo($metadata['file']);
	  $resized_images = $metadata['image_meta']['resized_images'];
	  
	  // Delete the resized images
	  foreach ($resized_images as $dims) {

	    // Get the resized images filename
	    $file = $wp_upload_dir['basedir'] . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];

	    // Delete the resized image (if it has not yet been deleted)
	    @unlink($file);

	  }

	}
endif;

/**
 * Get Thumbnail.
 *
 * @return mixed
 */
function wprm_thumb($url, $width, $height=0, $align='') {
  return mr_image_resize($url, $width, $height, true, $align, false);
}