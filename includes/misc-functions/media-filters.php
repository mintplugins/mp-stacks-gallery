<?php 
/**
 * This file contains the function which hooks to a brick's media output
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
 * This function hooks to the brick output. If it is supposed to be a 'gallery_item', then it will output the gallery
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_media_output_gallery($default_media_output, $mp_stacks_media_type, $post_id){
	
	//If this stack media type is set to be an image	
	if ($mp_stacks_media_type == 'gallery'){
		
		//Set default value for $media_output to NULL
		$media_output = NULL;	
		
		//Get Gallery Source Type
		$gallery_source = get_post_meta($post_id, 'gallery_source', true);
		
		//If Gallery Source is flickr
		if ( $gallery_source == 'flickr' ){
			
			//Get Flickr Photoset ID
			$gallery_photoset_id = get_post_meta($post_id, 'gallery_flickr_photoset_id', true);	
			
			//Get Row Height
			$gallery_justified_row_height = get_post_meta($post_id, 'gallery_jusitified_row_height', true);	
			
			//I'd like to use wp_localize_script but that won't work because it's settings per brick
			?>
            <script>
				mp_stacks_gallery_justified( "<?php echo $gallery_photoset_id; ?>", "<?php echo $gallery_justified_row_height; ?>" );
			</script>
            <?php
			
		}
		
		//Get Gallery Output
		$gallery_output = '<div class="mp-stacks-gallery">';
			
			$gallery_output .= '<div class="mp-stacks-gallery-justified"><div class="mp-stacks-gallery-picrow"></div></div>';
		
		$gallery_output .= '</div>';
		
		//Media output
		$media_output .= $gallery_output;
		
		//Return
		return $media_output;
	}
	else{
		//Return
		return $default_media_output;
	}
}
add_filter('mp_stacks_brick_media_output', 'mp_stacks_brick_media_output_gallery', 10, 3);