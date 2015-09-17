<?php
/**
 * This file contains the enqueue scripts function for the gallery plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for gallery 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue Admin JS for Ajax
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param  	 array $scripts An array containing the urls for each script as a key>value pair. Each key is what you'd use as the 'handle' in a wp_enqueue_scripts
 * @return   array $scripts The incoming array with our additional scripts added. These will be added to the Brick Editor footer upon ajax completion.
 */
function mp_stacks_gallery_ajax_admin_scripts( $scripts, $metabox_id ){
	
	if ( $metabox_id != 'mp_stacks_gallery_metabox' ){
		return $scripts;	
	}

	//Enqueue Admin JS
	$scripts['mp_stacks_gallery_admin'] = plugins_url( 'js/gallery-admin.js?ver=' . MP_STACKS_GALLERY_VERSION, dirname( __FILE__ ) );
	
	return $scripts;

}
add_filter( 'mp_core_metabox_ajax_admin_js_scripts', 'mp_stacks_gallery_ajax_admin_scripts', 10, 2 );

/**
 * Enqueue Admin CSS for Ajax
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param  	 array $stylesheets An array containing the urls for each stylesheet as a key>value pair. Each key is what you'd use as the 'handle' in a wp_enqueue_scripts
 * @return   array $stylesheets The incoming array with our additional stylesheet urls added. These will be added to the Brick Editor <head> upon ajax completion.
 */
function mp_stacks_gallery_ajax_admin_css( $stylesheets, $metabox_id ){
	
	if ( $metabox_id != 'mp_stacks_gallery_metabox' ){
		return $stylesheets;	
	}
	
	//Enqueue Admin CSS
	$stylesheets['mp_stacks_gallery_css'] = plugins_url( 'css/admin-gallery.css?ver=' . MP_STACKS_GALLERY_VERSION, dirname( __FILE__ ) );
	
	return $stylesheets;

}
add_filter( 'mp_core_metabox_ajax_admin_css_stylesheets', 'mp_stacks_gallery_ajax_admin_css', 10, 2 );