<?php
/**
 * This page contains functions for modifying the metabox for gallery as a media type
 *
 * @link http://moveplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Gallery
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2013, Move Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Gallery as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://moveplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_gallery_create_meta_box(){	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_gallery_add_meta_box = array(
		'metabox_id' => 'mp_stacks_gallery_metabox', 
		'metabox_title' => __( '"Gallery"  - Media Type', 'mp_stacks_gallery'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_gallery_items_array = array(
		array(
			'field_id'			=> 'gallery_settings_description',
			'field_title' 	=> __( 'Add Gallery', 'mp_stacks_gallery'),
			'field_description' 	=> '<br /><a href="#" class="mp-stacks-gallery-meta-button">' . __( 'Add Gallery', 'mp_stacks_gallery' ) ,
			'field_type' 	=> 'textbox',
			'field_value' => '',
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_gallery_add_meta_box = has_filter('mp_stacks_gallery_meta_box_array') ? apply_filters( 'mp_stacks_gallery_meta_box_array', $mp_stacks_gallery_add_meta_box) : $mp_stacks_gallery_add_meta_box;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_gallery_items_array = has_filter('mp_stacks_gallery_items_array') ? apply_filters( 'mp_stacks_gallery_items_array', $mp_stacks_gallery_items_array) : $mp_stacks_gallery_items_array;
	
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_gallery_meta_box;
	$mp_stacks_gallery_meta_box = new MP_CORE_Metabox($mp_stacks_gallery_add_meta_box, $mp_stacks_gallery_items_array);
}
add_action('plugins_loaded', 'mp_stacks_gallery_create_meta_box');