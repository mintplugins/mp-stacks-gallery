<?php
/**
 * This page contains functions for modifying the metabox for gallery as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Gallery
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Gallery as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_gallery_add_media_type( $mp_stacks_media_items_array ){	
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_media_items_array[0]['field_select_values']['gallery'] = 'Gallery';
	$mp_stacks_media_items_array[1]['field_select_values']['gallery'] = 'Gallery';
	
	return $mp_stacks_media_items_array;

}
add_filter('mp_stacks_content_types_array', 'mp_stacks_gallery_add_media_type');