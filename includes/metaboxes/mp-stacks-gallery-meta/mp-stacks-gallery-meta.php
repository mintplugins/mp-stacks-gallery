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
		'metabox_title' => __( '"Gallery" Content-Type', 'mp_stacks_gallery'), 
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
			'field_id'			=> 'gallery_source',
			'field_title' 	=> __( 'Gallery Source', 'mp_stacks_gallery'),
			'field_description' 	=> __( 'Where should this gallery get images from?', 'mp_stacks_gallery' ) ,
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => array( 'wp' => 'This WordPress', 'flickr' => 'Flickr' )
		),
		array(
			'field_id'			=> 'gallery_wp_gallery_shortcode',
			'field_title' 	=> __( 'Gallery Images: ', 'mp_stacks_gallery'),
			'field_description' 	=> '<a href="#" class="mp-stacks-gallery-meta-button">' . __( 'Manage', 'mp_stacks_gallery' ) . '</a>' ,
			'field_type' 	=> 'hidden',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_flickr_photoset_url',
			'field_title' 	=> __( 'Flickr Photoset', 'mp_stacks_gallery'),
			'field_description' 	=> '<br />' . __( 'Enter your Flickr PhotoSet URL', 'mp_stacks_gallery' ),
			'field_type' 	=> 'url',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_justified_row_height',
			'field_title' 	=> __( 'Max Row Height', 'mp_stacks_gallery'),
			'field_description' 	=> '<br />' . __( 'Enter the maximum row height which holds the images', 'mp_stacks_gallery' ),
			'field_type' 	=> 'number',
			'field_value' => '',
		),
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

/**
 * Create filter to set the description to be the link to the saved file. 
 * We do it in a filter because it makes the post_id available to us
 */ 
function mp_stacks_gallery_gallery_wp_gallery_shortcode($description, $post_id){
	
	//Get images attached to WordPress gallery
	$mp_stacks_gallery_shortcode = get_post_meta( $post_id, 'gallery_wp_gallery_shortcode', true);
	
	$photos_string = NULL;
	
	if (!empty($mp_stacks_gallery_shortcode)){
		//Content url
		$wp_content_url = content_url();
		
		//Extract shortcode values
		$photos_array_for_loop = explode( '"', $mp_stacks_gallery_shortcode );
		$photos_array_for_loop = explode( ',', $photos_array_for_loop[1] );
		
		//Assemble javascript array
		foreach( $photos_array_for_loop as $key => $post_id ){
			
			//get photo meta
			$photo_meta = wp_get_attachment_metadata( $post_id );
					
			if ( isset($photo_meta['file'] ) ){		
				//Build photo string
				$photos_string .= '<a href="#" class="mp-stacks-gallery-meta-button"><img src=' . $wp_content_url .'/uploads/' . $photo_meta['file'] . ' /></a>';
			}
			
		}
	}
	
	return $description . '<div class="mp-stacks-gallery-meta-button mp-stacks-gallery-preview">' . $photos_string . '</div>';	
	
}
add_filter('mp_gallery_wp_gallery_shortcode_description', 'mp_stacks_gallery_gallery_wp_gallery_shortcode', 10, 2);