<?php

		public  $wp_id; 
		public  $name; 
		public  $file_path;
		public  $width;
		public  $height;
		public  $size;
		public  $thumbnail_image_path;
		public  $highres_image;
		public  $areas;
		public  $map_scale;
		public  $map_offset_x;
		public  $map_offset_y;
		public  $dimentions;
		
		echo '<br>';
		echo $LMO->name;
		echo '<br>';
		echo  $LMO->get_thumbnail_html();
	
		
		echo '<br>';

?>