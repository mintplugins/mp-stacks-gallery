jQuery(document).ready(function($) {
	
	function reset_mp_stacks_gallery_options(){
		//Hide all Gallery Options
		$('.mp_field_gallery_wp_gallery_shortcode').css('display', 'none');	
		$('.mp_field_gallery_flickr_photoset_id').css('display', 'none');	
		$('.mp_field_gallery_jusitified_row_height').css('display', 'none');	
				
	
		//Show correct media type metaboxes by looping through each item in the 1st drodown
		$("#mp_stacks_gallery_metabox .gallery_source>option:selected").map(function() { 
						
			if ( $(this).val() == 'wp' ){
				
				//Show WP Gallery Options
				$('.mp_field_gallery_wp_gallery_shortcode').css('display', 'block');	
				
			}
			
			if ( $(this).val() == 'flickr' ){
				
				//Show Flickr Gallery Options
				$('.mp_field_gallery_flickr_photoset_id').css('display', 'block');	
				$('.mp_field_gallery_jusitified_row_height').css('display', 'block');	
				
			}
			
			
		});
	}
	
	$('#mp_stacks_gallery_metabox .gallery_source').change(function() {
		reset_mp_stacks_gallery_options();
	});
	
	reset_mp_stacks_gallery_options();
	
});