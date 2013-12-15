<?php
/**
 * This file contains the enqueue scripts function for the gallery plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
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
 * Enqueue css and js
 *
 * Filter: mp_stacks_gallery_css_location
 */
function mp_stacks_gallery_enqueue_scripts(){
		
	//Enqueue gallery CSS
	wp_enqueue_style( 'mp_stacks_gallery_css', plugins_url( 'css/gallery.css', dirname( __FILE__ ) ) );
	
	//Enqueue gallery CSS
	wp_enqueue_script( 'mp_stacks_gallery_js', plugins_url( 'js/gallery-front-end.js', dirname( __FILE__ ) ), array('jquery') );
	
}
 
/**
 * Enqueue css face for social grid
 */
add_action( 'wp_enqueue_scripts', 'mp_stacks_gallery_enqueue_scripts' );

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_gallery_css_location
 */
function mp_stacks_gallery_admin_enqueue_scripts(){
	
	//Enqueue Admin gallery js
	wp_enqueue_script( 'mp_stacks_gallery_admin', plugins_url( 'js/gallery-admin.js', dirname( __FILE__ ) ) );
	
	//Enqueue Admin Features CSS
	wp_enqueue_style( 'mp_stacks_gallery_css', plugins_url( 'css/admin-gallery.css', dirname( __FILE__ ) ) );

}
add_action( 'admin_enqueue_scripts', 'mp_stacks_gallery_admin_enqueue_scripts' );