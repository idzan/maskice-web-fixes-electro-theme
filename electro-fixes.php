<?php
/*
Plugin Name: Electro Theme fixes
Plugin URI: https://maskice.hr/
Description: Lot of fixes for web and tweaks powered by Marko Idzan. Sindikat policije fix for their orders, SKU Fix
Version: 1.0
Author: Idzan
Author URI: https://idzan.eu
License: GPLv2 or later
*/

// Zoom on Product Disable
add_action( 'after_setup_theme', 'ec_child_remove_product_gallery_zoom_support', 20 );

function ec_child_remove_product_gallery_zoom_support() {
  remove_theme_support( 'wc-product-gallery-zoom' );
}


// Gravity Forms Tweaks & fixes
add_filter( 'gform_username', 'auto_username', 10, 4 );
function auto_username( $username, $feed, $form, $entry ) {
    // Update 2.3 and 2.6 with the id numbers of your Name field inputs. e.g. If your Name field has id 1 the inputs would be 1.3 and 1.6
    $username = strtolower( rgar( $entry, '2.3' ) );
 
    if ( empty( $username ) ) {
        return $username;
    }
 
    if ( ! function_exists( 'username_exists' ) ) {
        require_once( ABSPATH . WPINC . '/registration.php' );
    }
 
    if ( username_exists( $username ) ) {
        $i = 2;
        while ( username_exists( $username . $i ) ) {
            $i++;
        }
        $username = $username . $i;
    };
 
    return $username;
}

// DISABLE BOTH default WordPress new user notification emails
if ( ! function_exists( 'wp_new_user_notification' ) ) :
    function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {
        return;
    }
endif;

// Fix for issue where too many variations causes the front end to not pre-load  all variations and rely on AJAX.
function custom_wc_ajax_variation_threshold( $qty, $product )
{
    return 5000;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'custom_wc_ajax_variation_threshold', 10, 2 );

// Remove jquery migrate for enhanced performance
function remove_jquery_migrate_maskice($scripts) {
   if ( is_admin() ) return;
   $scripts->remove( 'jquery' );
   $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate_maskice' );


// Remove Query Strings from JS and CSS
function maskice_remove_query_strings( $src ) {
  if( strpos( $src, '?ver=' ) )
    $src = remove_query_arg( 'ver', $src );
    return $src;
  }
add_filter( 'style_loader_src', 'maskice_remove_query_strings', 10, 2 );
add_filter( 'script_loader_src', 'maskice_remove_query_strings', 10, 2 );


function maskice_remove_script_version( $src ){
  $parts = explode( '?ver', $src );
  return $parts[0];
}
add_filter( 'script_loader_src', 'maskice_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'maskice_remove_script_version', 15, 1 );

// Remove login shake
function my_login_head() {
    remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'my_login_head');

// Remove alt from images to show on front
function mytheme_wp_get_attachment_image_attributes( $attr ) {
 
unset($attr['title']);
 
 return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mytheme_wp_get_attachment_image_attributes' );

function mytheme_wp_get_attachment_image_attributes_alts( $attr ) {
 
unset($attr['alt']);
 
 return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mytheme_wp_get_attachment_image_attributes_alts' );