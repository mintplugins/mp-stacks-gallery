<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Gallery
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
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
function mp_stacks_brick_content_output_gallery($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type is set to be an image	
	if ($mp_stacks_content_type == 'gallery'){
		
		global $mp_stacks_gallery_js_output;
		
		//Set default value for $content_output to NULL
		$content_output = NULL;	
		
		//Get Gallery Source Type
		$gallery_source = get_post_meta($post_id, 'gallery_source', true);
		
		//If Gallery Source is flickr
		if ( $gallery_source == 'flickr' ){
			
			//Get Flickr Photoset ID
			$gallery_photoset_id = get_post_meta($post_id, 'gallery_flickr_photoset_url', true);	
			
			//If nothing has been entered into the flickr photoset URL (no uploaded images)
			if ( empty($gallery_photoset_id)){
				return $default_content_output;	
			}
			
			//If this doesn't contain the word "Set" in the URL
			if ( strpos($gallery_photoset_id, 'set/')){
				return $default_content_output;	
			}
			
			//Get Row Height
			$gallery_justified_row_height = get_post_meta($post_id, 'gallery_justified_row_height', true);	
			$gallery_justified_row_height = !empty($gallery_justified_row_height) ? $gallery_justified_row_height : 200;
			
			//Assemble the JS we'll need to fire for this gallery in the footer
			$js_output = '
            <script>
				mp_stacks_gallery_justified( "' . $gallery_photoset_id . '", "' . $gallery_justified_row_height . '", "' . $post_id . '" );
			</script>';
			
		}
		//If Gallery Source is WordPress
		elseif ( $gallery_source == 'wp' ){
			
			//Get mp_stacks_gallery shortcode
			$mp_stacks_gallery_shortcode = get_post_meta($post_id, 'gallery_wp_gallery_shortcode', true);	
			
			//If nothing has been entered into the shortcode (no uploaded images)
			if ( empty($mp_stacks_gallery_shortcode)){
				return $default_content_output;	
			}
			
			//Extract shortcode values
			$photos_array_for_loop = explode( '"', $mp_stacks_gallery_shortcode );
			$photos_array_for_loop = explode( ',', $photos_array_for_loop[1] );
			
			//Set default empty array for photos
			$photos_array = array();
			
			$wp_content_url = content_url();
			
			//Get Row Height
			$gallery_justified_row_height = get_post_meta($post_id, 'gallery_justified_row_height', true);	
			$gallery_justified_row_height = !empty($gallery_justified_row_height) ? $gallery_justified_row_height : 200;
			
			//Assemble javascript array
			foreach( $photos_array_for_loop as $key => $image_id ){
				
				//get photo meta
				$photo_meta = wp_get_attachment_metadata( $image_id );
				
				$photo_attributes = wp_get_attachment_image_src( $image_id, 'large' );
					
				if ( isset( $photo_meta['image_meta'] ) ){							
					//set values for js
					$photos_array[$key]['url_wp'] = $photo_attributes[0];
					$photos_array[$key]['width_wp'] = $photo_attributes[1];
					$photos_array[$key]['height_wp'] = $photo_attributes[2];
					$photos_array[$key]['title'] = $photo_meta['image_meta']['title'];
				}
				
			}
			
			//Assemble the JS we'll need to fire for this gallery in the footer
			$js_output = 
			'<script>
				
				var mp_stacks_gallery_wp_array_' . $post_id . ' = [';
					
					//Assemble javascript array
					//Loop through each photo in the array
					foreach( $photos_array as $photo ){
						
						$js_output .= '{';
						
						//Set Counter to 1
						$counter = 1;
						
						//Set total number of items in this photo
						$total = count($photo);
					
						//Loop through the values for each photo in the array
						foreach( $photo as $key => $value ){
							
							//If we have a value set for this key
							if ($value){
								
								$js_output .= ' "' . $key . '": "' . $value . '"'; 
								
								//increment counter
								$counter = $counter + 1;
								
								//If we aren't on our last photo info item, put a comma
								if ( $counter < $total ){
									$js_output .= ', ';	
								}
								
							}	
						}
						
						$js_output .= '},';
					}
				
				$js_output .= '];
				
				mp_stacks_gallery_justified( mp_stacks_gallery_wp_array_' . $post_id . ', "' . $gallery_justified_row_height . '", "' . $post_id . '" );
				
			</script>';
			
		}
		
		//Add the JS to the global variable which will handle all mp_stacks_galleries on this page
		$mp_stacks_gallery_js_output[$post_id] = $js_output;
			
		//Get Gallery Output
		$gallery_output = '<div class="mp-stacks-gallery">';
			
			$gallery_output .= '<div class="mp-stacks-gallery-justified"><div class="mp-stacks-gallery-picrow"></div></div>';
		
		$gallery_output .= '</div>';
		
		//Content output
		$content_output .= $gallery_output;
		
		//Return
		return $content_output;
	}
	else{
		//Return
		return $default_content_output;
	}
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_gallery', 10, 3);

function mp_stacks_gallery_output_js(){
	
	global $mp_stacks_gallery_js_output;
	
	if ( empty( $mp_stacks_gallery_js_output ) ){
		return;	
	}
	
	if ( wp_script_is( 'mp_stacks_gallery_js', 'done' ) ) {
		foreach( $mp_stacks_gallery_js_output as $gallery_js ){
			echo $gallery_js;
		}
	}
}
add_action( 'wp_footer', 'mp_stacks_gallery_output_js', 100);