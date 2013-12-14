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
 * This function hooks to the brick output. If it is supposed to be a 'feature', then it will output the gallery
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
		
		//Get Gallery Metabox Repeater Array
		$gallery_repeaters = get_post_meta($post_id, 'mp_gallery_repeater', true);
		
		//Gallery per row
		$gallery_per_row = get_post_meta($post_id, 'gallery_per_row', true);
		$gallery_per_row = empty( $gallery_per_row ) ? '2' : $gallery_per_row;
		
		//Feature alignment
		$feature_alignment = get_post_meta($post_id, 'feature_alignment', true);
		$feature_alignment = empty( $feature_alignment ) ? 'left' : $feature_alignment;
		
		//Get Gallery Output
		$gallery_output = '<div class="mp-stacks-gallery">';
		
		//Get Gallery Output
		$gallery_output .= '
		<style scoped>
			.mp-stacks-feature{ 
				color:' . get_post_meta($post_id, 'feature_text_color', true) . ';
				width:' . (100/$gallery_per_row) .'%;
				text-align:' . $feature_alignment . ';
			}
			@media screen and (max-width: 600px){
				.mp-stacks-feature{ 
					width:' . '100%;
				}
			}';
			
			$gallery_output .= $feature_alignment != 'left' ? NULL : '.mp-stacks-gallery-icon{ margin: 0px 10px 0px 0px; }';
		$gallery_output .= '</style>';
		
		//Set counter to 0
		$counter = 1;
		
		if ($gallery_repeaters ){
			
			//Loop through each feature
			foreach( $gallery_repeaters as $gallery_repeater ){
							
					$gallery_output .= '<div class="mp-stacks-feature">';
					
						$gallery_output .= '<div class="mp-stacks-gallery-icon ' . $gallery_repeater['feature_icon'] . '">';
							
							$gallery_output .= '<div class="mp-stacks-gallery-icon-title">' . $gallery_repeater['feature_title'] . '</div>';
							
						$gallery_output .= '</div>';
						
						$gallery_output .= $feature_alignment == 'center' ? '<div class="mp-stacks-gallery-clearedfix"></div>' : NULL;
						
						$gallery_output .= '<div class="mp-stacks-gallery-title">';
						
							$gallery_output .= $gallery_repeater['feature_title'];
							
						$gallery_output .= '</div>';
						
						//Add clear div to bump gallery below title and icon
						$gallery_output .= '<div class="mp-stacks-gallery-clearedfix"></div>';
						
						$gallery_output .= '<div class="mp-stacks-gallery-text">';
						
							$gallery_output .= $gallery_repeater['feature_text'];
								
						$gallery_output .= '</div>';
				
					$gallery_output .= '</div>';
					
					if ( $gallery_per_row == $counter ){
						
						//Add clear div to bump a new row
						$gallery_output .= '<div class="mp-stacks-gallery-clearedfix"></div>';
						
						//Reset counter
						$counter = 1;
					}
					else{
						
						//Increment Counter
						$counter = $counter + 1;
						
					}
					
			}
		}
		
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