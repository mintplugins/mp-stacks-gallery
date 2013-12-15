function mp_stacks_gallery_justified( mp_stacks_photoset_id, row_height ){
		
	jQuery(document).ready(function($) {
		
		var photo_array = null;
		
		if ( !row_height ){ row_height = 200; }
		
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
		
		$.getJSON("http://api.flickr.com/services/rest/?format=json&extras=url"+url_size+"&method=flickr.photosets.getPhotos&photoset_id="+mp_stacks_photoset_id+"&api_key=dbb49a0e2dcc3958834f1b92c072be62&jsoncallback=?", null,
		function(data, status) {
						
			photo_array = data.photoset.photo;
			
			alert(JSON.stringify(photo_array, null, 4));
			
			processPhotos(photo_array);
			
			alert(JSON.stringify(photo_array, null, 4));
			
			$(window).resize(function() {
				processPhotos(photo_array);
			});
			
		});
			
		function processPhotos(photos){
				
			var total_images = photos.length;
			
			$('.mp-stacks-gallery-justified').empty();
			div_rows = $('.mp-stacks-gallery-justified').prepend('<div class="mp-stacks-gallery-picrow"></div>');
			
			// get row width - this is fixed.
			var row_width = div_rows.eq(0).innerWidth();
			
			// margin width
			var border = 5;
			
			// store relative widths of all images (scaled to match estimate height above)
			var widths_array = [];
			$.each(photos, function(key, val) {
				var photo_width = parseInt(val["width" + url_size], 10);
				var photo_height = parseInt(val["height" + url_size], 10);
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
					var current_row = $("div.mp-stacks-gallery-picrow");
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
					var next_row = $(current_row).clone().appendTo('.mp-stacks-gallery-justified');
					
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
					
					var img =  $('<img/>', {class: "photo", src: photo["url"+url_size], width: photo_width, height: photo_height }).css("margin", border + "px");
					
					current_row.append(img);
					
					i++;
					
				}
				
				// if total width is slightly smaller than 
				// actual div width then add 1 to each 
				// photo width till they match
				i = 0;
				while( tw < row_width )
				{
					var img1 = current_row.find("img:nth-child(" + (i + 1) + ")");
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
					var img2 = current_row.find("img:nth-child(" + (i + 1) + ")");
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
