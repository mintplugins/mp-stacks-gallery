jQuery(document).ready(function($){
	wp.media.mp_stacks_gallery_editor = {
		 
		frame: function() {
			if ( this._frame )
				return this._frame;
	
				var selection = this.select();
				
				this._frame = wp.media({
					id:         'mp-stacks-gallery-edit-frame',                
					frame:      'post',
					state:      'gallery-edit',
					title:      wp.media.view.l10n.editGalleryTitle,
					editing:    true,
					multiple:   true,
					selection:  selection
				});
			
			this._frame.on( 'update', 
				function() {
					var controller = wp.media.mp_stacks_gallery_editor._frame.states.get('gallery-edit');
					var library = controller.get('library');
					// Need to get all the attachment ids for gallery
					var ids = library.pluck('id');
									
					var shortcode = '[mp_stacks_gallery ids="';
					
					var counter = 0;
					
					//Loop through each image selected by the user			
					$.each(ids, function( index, value ) {
						
						//Increment a counter
						counter++;
						
						//Add the new image ID to the shortcode string
						shortcode = shortcode + value;
						
						//If this isn't the last iteration, add a comma to the string
						if ( counter != ids.length ){
							 shortcode = shortcode + ','	
						}
					});
					
					//Build shortcode and place it in the text field 
					$('#gallery_wp_gallery_shortcode').val( shortcode + '"]' );

				});

			
			return this._frame;
		},
	 
		init: function() {
			$('.mp-stacks-gallery-meta-button').click( function( event ) {
				
				event.preventDefault();
	 
				wp.media.mp_stacks_gallery_editor.frame().open();
	 
			});
		},
		
		// Gets initial gallery-edit images. Function modified from wp.media.gallery.edit
		// in wp-includes/js/media-editor.js.source.html
		select: function() {
				var shortcode = wp.shortcode.next( 'mp_stacks_gallery', $('#gallery_wp_gallery_shortcode').val() ),
					defaultPostId = wp.media.gallery.defaults.id,
					attachments, selection;
		 
			// Bail if we didn't match the shortcode or all of the content.
			if ( ! shortcode )
				return;
		 
			// Ignore the rest of the match object.
			shortcode = shortcode.shortcode;
		 
			if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
				shortcode.set( 'id', defaultPostId );
		 
			attachments = wp.media.gallery.attachments( shortcode );
			selection = new wp.media.model.Selection( attachments.models, {
				props:    attachments.props.toJSON(),
				multiple: true
			});
			 
			selection.gallery = attachments.gallery;
		 
			// Fetch the query's attachments, and then break ties from the
			// query to allow for sorting.
			selection.more().done( function() {
				// Break ties with the query.
				selection.props.set({ query: false });
				selection.unmirror();
				selection.props.unset('orderby');
			});
		 
			return selection;
		},
	};
 
    $( wp.media.mp_stacks_gallery_editor.init );
	
});

//Set the right Brick Options based on the selected settings
jQuery(document).ready(function($) {
	
	function reset_mp_stacks_gallery_options(){
		//Hide all Gallery Options
		$('.mp_field_gallery_wp_gallery_shortcode').css('display', 'none');	
		$('.mp_field_gallery_flickr_photoset_url').css('display', 'none');	
		$('.mp_field_gallery_jusitified_row_height').css('display', 'none');	
				
	
		//Show correct media type metaboxes by looping through each item in the 1st drodown
		$("#mp_stacks_gallery_metabox .gallery_source>option:selected").map(function() { 
						
			if ( $(this).val() == 'wp' ){
				
				//Show WP Gallery Options
				$('.mp_field_gallery_wp_gallery_shortcode').css('display', 'block');
				$('.mp_field_gallery_jusitified_row_height').css('display', 'block');		
				
			}
			
			if ( $(this).val() == 'flickr' ){
				
				//Show Flickr Gallery Options
				$('.mp_field_gallery_flickr_photoset_url').css('display', 'block');	
				$('.mp_field_gallery_jusitified_row_height').css('display', 'block');	
				
			}
			
			
		});
	}
	
	$('#mp_stacks_gallery_metabox .gallery_source').change(function() {
		reset_mp_stacks_gallery_options();
	});
	
	reset_mp_stacks_gallery_options();
	
});