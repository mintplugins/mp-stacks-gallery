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
	
	$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
	$subject = file_get_contents( plugins_url( '/fonts/font-awesome-4.0.3/css/font-awesome.css', dirname( dirname( __FILE__ ) ) ) );
	
	preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
	
	$icons = array();

	foreach($matches as $match){
		$icons[$match[1]] = $match[1];
	}	
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_gallery_items_array = array(
		array(
			'field_id'			=> 'gallery_settings_description',
			'field_title' 	=> __( 'Overall Gallery Settings', 'mp_stacks_gallery'),
			'field_description' 	=> '<br />Choose the overall settings for your gallery' ,
			'field_type' 	=> 'basictext',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_per_row',
			'field_title' 	=> __( 'Gallery Per Row', 'mp_stacks_gallery'),
			'field_description' 	=> 'How many gallery do you want from left to right before a new row starts?',
			'field_type' 	=> 'number',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_text_color',
			'field_title' 	=> __( 'Gallery Text Color', 'mp_stacks_gallery'),
			'field_description' 	=> 'Enter the text color for all of these gallery',
			'field_type' 	=> 'colorpicker',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_alignment',
			'field_title' 	=> __( 'Gallery Alignment', 'mp_stacks_gallery'),
			'field_description' 	=> 'Select how you want the gallery to be aligned' ,
			'field_type' 	=> 'select',
			'field_value' => '',
			'field_select_values' => array( 'left' => 'Left', 'center' => 'Center' ),
		),
		array(
			'field_id'			=> 'gallery_description',
			'field_title' 	=> __( '<br />Add Your Gallery Below', 'mp_stacks_gallery'),
			'field_description' 	=> '<br />Open up the following areas to add/remove new gallery.' ,
			'field_type' 	=> 'basictext',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'gallery_title',
			'field_title' 	=> __( 'Gallery Title', 'mp_stacks_gallery'),
			'field_description' 	=> 'Enter the title of this gallery',
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_gallery_repeater'
		),
		array(
			'field_id'			=> 'gallery_icon',
			'field_title' 	=> __( 'Gallery Icon', 'mp_stacks_gallery'),
			'field_description' 	=> 'Select the icon to use for this gallery',
			'field_type' 	=> 'iconfontpicker',
			'field_value' => '',
			'field_select_values' => $icons,
			'field_repeater' => 'mp_gallery_repeater'
		),
		array(
			'field_id'			=> 'gallery_text',
			'field_title' 	=> __( 'Gallery Text (HTML Allowed)', 'mp_stacks_gallery'),
			'field_description' 	=> 'Enter the text for this gallery.',
			'field_type' 	=> 'wp_editor',
			'field_value' => '',
			'field_repeater' => 'mp_gallery_repeater'
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