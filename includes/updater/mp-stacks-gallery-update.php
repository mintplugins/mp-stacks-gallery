<?php
/**
 * This file contains the function keeps the MP Stacks Gallery plugin up to date.
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Gallery
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Check for updates for the MP Stacks Gallery Plugin by creating a new instance of the MP_CORE_Plugin_Updater class.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
 if (!function_exists('mp_stacks_gallery_update')){
	function mp_stacks_gallery_update() {
		$args = array(
			'software_name' => 'MP Stacks Gallery', //<- The exact name of this Plugin.
			'software_api_url' => 'http://moveplugins.com',//The URL where Gallery and mp_repo are installed and checked
			'software_filename' => 'mp-stacks-gallery.php',
			'software_licensed' => true, //<-Boolean
		);
		
		//Since this is a plugin, call the Plugin Updater class
		$mp_stacks_gallery_plugin_updater = new MP_CORE_Plugin_Updater($args);
	}
 }
add_action( 'init', 'mp_stacks_gallery_update' );
