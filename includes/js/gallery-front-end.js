function mp_stacks_gallery_justified( mp_stacks_photoset_url_or_array, row_height, brick_id ){
	
	jQuery(document).ready(function($) {
		
		if ( !row_height ){ row_height = 200; }
		
		if ( typeof mp_stacks_photoset_url_or_array == 'object'){
			
			//This gallery is from WordPress
			
			var url_size = "_wp";
			
			//Process the Photos
			processPhotos(mp_stacks_photoset_url_or_array, mp_stacks_photoset_url_or_array, brick_id);
			
			//Process the photos upon screen resize				
			//Function that waits for resize end - so we don't re-process while re-sizing
			var mp_stacks_gallery_resize_timer;
			jQuery(window).resize(function(){
				clearTimeout(mp_stacks_gallery_resize_timer);
				mp_stacks_gallery_resize_timer = setTimeout(mp_stacks_gallery_resize_end, 100);
			});
			
			//Custom Event which fires after resize has ended
			function mp_stacks_gallery_resize_end(){
				
				processPhotos(mp_stacks_photoset_url_or_array, mp_stacks_photoset_url_or_array, brick_id);
				
			}
			
		}
		else{
			
			//This gallery is from Flickr
			
			//Get PhotoSet ID from the Photoset URL
			var mp_stacks_photoset_id = mp_stacks_photoset_url_or_array.split( 'sets/');
			mp_stacks_photoset_id = mp_stacks_photoset_id[1].split( '/');
			mp_stacks_photoset_id = mp_stacks_photoset_id[0];
			
			var photo_array = null;
			var photo_array_o = null;
			
			//Makes sure images load at X times the size they show at. EG "2" equals double. "3" equals triple.
			var retina_multiplier = 2;
			
			if ( row_height < (75/retina_multiplier) ){
				var url_size = "_s";
			}
			else if ( row_height < (240/retina_multiplier) ){
				var url_size = "_m";
			}
			else if ( row_height < (320/retina_multiplier) ){
				var url_size = "_n";
			}
			else if ( row_height < (640/retina_multiplier) ){
				var url_size = "_z";
			}
			else if ( row_height < (800/retina_multiplier) ){
				var url_size = "_c";
			}
			else if ( row_height > (800/retina_multiplier) ){
				var url_size = "_o";
			}
			
			//Get thumbnail sized images
			$.getJSON("http://api.flickr.com/services/rest/?format=json&extras=url"+url_size+"&method=flickr.photosets.getPhotos&photoset_id="+mp_stacks_photoset_id+"&api_key=dbb49a0e2dcc3958834f1b92c072be62&jsoncallback=?", null,
			function(data, status) {
				
				//Assign array to this var
				photo_array = data.photoset.photo;
				
				//If the feed we already have isn't the "o" size
				if ( url_size != "_o" ){
					
					//Get original sized photos for popups
					$.getJSON("http://api.flickr.com/services/rest/?format=json&extras=url_o&method=flickr.photosets.getPhotos&photoset_id="+mp_stacks_photoset_id+"&api_key=dbb49a0e2dcc3958834f1b92c072be62&jsoncallback=?", null,
					function(data_o, status_o) {
						
						//Assign originals array
						photo_array_o = data_o.photoset.photo;
						
						//Process the Photos
						processPhotos(photo_array, photo_array_o, brick_id);
						
						//Process the photos upon screen resize				
						//Function that waits for resize end - so we don't re-process while re-sizing
						var mp_stacks_gallery_resize_timer;
						jQuery(window).resize(function(){
							clearTimeout(mp_stacks_gallery_resize_timer);
							mp_stacks_gallery_resize_timer = setTimeout(mp_stacks_gallery_resize_end, 100);
						});
						
						//Custom Event which fires after resize has ended
						function mp_stacks_gallery_resize_end(){
							
							processPhotos(photo_array, photo_array_o, brick_id);
							
						}
										
					});
				}
				//If we don't need to get the originals array (because we already got it)
				else{
							
					//Make the originals array equal to the thumbnails array		
					photo_array_o = photo_array;
					
					//Process the Photos
					processPhotos(photo_array, photo_array_o, brick_id);
					
					//Process the photos upon screen resize				
					//Function that waits for resize end - so we don't re-process while re-sizing
					var mp_stacks_gallery_resize_timer;
					jQuery(window).resize(function(){
						clearTimeout(mp_stacks_gallery_resize_timer);
						mp_stacks_gallery_resize_timer = setTimeout(mp_stacks_gallery_resize_end, 100);
					});
					
					//Custom Event which fires after resize has ended
					function mp_stacks_gallery_resize_end(){
						
						processPhotos(photo_array, photo_array_o, brick_id);
						
					}
				
				}
				
			});		
		}

		//Function which will process the photos
		function processPhotos(photos, photos_o, brick_id){
			
			//Get the number of photos in the array
			var total_images = photos.length;
			
			//Empty out the area where we'll put the photos
			$('#mp-brick-' + brick_id + ' .mp-stacks-gallery-justified').empty();
			
			//Add a row
			div_rows = $('#mp-brick-' + brick_id + ' .mp-stacks-gallery-justified').prepend('<div class="mp-stacks-gallery-picrow"></div>');
			
			// Get row width - this is fixed.
			var row_width = div_rows.eq(0).innerWidth();
			
			// margin width
			var border = 5;
			
			//In order to find the widths, we need to use an extension on the width key of either "_o" for Flickr or "_wp" for WordPress
			
			//If we're not coming from wordpress, its flickr so set it up to grab the original sizes
			if ( url_size != '_wp' ){
				width_finder_size = '_o';
			}
			//Otherwise grab it using the sizes we gave from WordPress
			else{
				width_finder_size = '_wp';
			}	
			
			// store relative widths of all images (scaled to match estimate height above)
			var widths_array = [];
			$.each(photos_o, function(key, val) {
				var photo_width = parseInt(val["width" + width_finder_size], 10);
				if ( !photo_width ){
					photo_width = 200;	
				}
				var photo_height = parseInt(val["height" + width_finder_size], 10);
				if ( !photo_height ){
					photo_height = 200;	
				}
				if( photo_height != row_height ) { photo_width = Math.floor(photo_width * (row_height / photo_height )); }
				widths_array.push(photo_width);
			});
			
			// total number of images appearing in all previous rows
			var baseline = 0; 
			var rowNum = 0;
			var total_rows = 1;
		
			//Loop through div rows on page
			while(rowNum < total_rows){
				
				//Increase the loop by one
				rowNum++;
					
				if ( !current_row ){
					var current_row = $('#mp-brick-' + brick_id + ' div.mp-stacks-gallery-picrow');
				}
				
				current_row.empty();
				
				// total width of images in this row - including margins
				var tw = 0;
				
				var photos_per_row = 0;
				
				// calculate width of images and number of images to view in this row.
				while( tw <= row_width )
				{				
					//If there are no more images
					if ( !widths_array[baseline + (photos_per_row) ] ){
						break;
					}
					
					//Find out how wide the content in this row is
					tw += widths_array[baseline + photos_per_row++] + border * 2;
				
				}
				
				// If there are still more images to be used, add another row
				if ( baseline < (total_images-photos_per_row) ){
					
					//Create the next row
					var next_row = $(current_row).clone().appendTo('#mp-brick-' + brick_id + ' .mp-stacks-gallery-justified');
					
					//Empty it out
					next_row.empty();
					
					//Place it on the page after our current row
					current_row.after(next_row);
					
					//Increase the total number of rows needed by one
					total_rows++;
				
				}
			
				// Ratio of actual width-of-row to total width-of-images to be used.
				var r = row_width / tw; 
				
				if ( r > 1 ){ r=1; row_width = tw; };
				
				// image number being processed
				var i = 0;
				
				// reset total width to be total width of processed images
				tw = 0;
				
				// new height is not original height * ratio
				var photo_height = Math.floor(row_height * r);
							
				while( i < photos_per_row )
				{
					var photo = photos[baseline + i];
				
					// Calculate new width based on ratio
					var photo_width = Math.floor(widths_array[baseline + i] * r);
										
					// add to total width with margins
					tw += photo_width + border * 2;
					
					if (photos_o[baseline + i]["url_o"]){
						original_size_url = photos_o[baseline + i]["url_o"];
					}
					else{
						original_size_url = photos_o[baseline + i]["url_wp"];
					}
					
					//If there's no url in the default url size, it's likely the image is too small to have a thumbnail created for it at this size by flickr
					//So instead we'll get it from the originals array
					if ( !photo["url"+url_size] ){
						photo_to_show_url = original_size_url;	
					}
					//If there is a url, grab it from the size we want rather than the original - which COULD be massive and take forever
					else{
						photo_to_show_url = photo["url"+url_size];
					}
					
					var a = $('<a>', {class: "mp_stacks_gallery_image_a", href: original_size_url}).css("margin", border + "px");
					var img =  $('<img/>', {class: "photo", src: photo_to_show_url, width: photo_width, height: photo_height });
					
					a.append(img);
					
					current_row.append(a);
					
					i++;
					
				}
				
				// if total width is slightly smaller than 
				// actual div width then add 1 to each 
				// photo width till they match
				i = 0;
				while( tw < row_width )
				{
					var img1 = current_row.find("a:nth-child(" + (i + 1) + ") img");
					img1.width(img1.width() + 1);
					i = (i + 1) % photos_per_row;
					tw++;
				}
				
				// if total width is slightly bigger than 
				// actual div width then subtract 1 from each 
				// photo width till they match
				i = 0;
				while( tw > row_width )
				{
					var img2 = current_row.find("a:nth-child(" + (i + 1) + ") img");
					img2.width(img2.width() - 1);
					i = (i + 1) % photos_per_row;
					tw--;
				}
				
				// set row height to actual height + margins
				current_row.height(photo_height + border * 2);
			
				baseline += photos_per_row;
				
				current_row = next_row;
			
				}
		}
	});
}

//Lightbox Gallery
jQuery(document).ready(function($) {
	//Gallery Images in Lightbox
	$('.mp-stacks-gallery').each(function() { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: '.mp_stacks_gallery_image_a', // the selector for gallery item
			type: 'image',
			gallery: {
			  enabled:true
			}
		});
	}); 
});