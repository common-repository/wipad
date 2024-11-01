<?php 
 // determine if we're answering an AJAX call or NO 
 global $is_ajax; $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);

 if($is_ajax){
   the_post();
   $the_content = json_encode("<div class='x-htmlcontent the_content'>".apply_filters('the_content',get_the_content())."</div>");
   $the_title   = json_encode(get_the_title());
   echo "[".$the_title.", ".$the_content."]";
   exit(0);
 }

 // redirect to the mail app with the anchor setted 
 header('Location: '.str_replace(get_bloginfo('wpurl')."/", get_bloginfo('wpurl')."/#/", get_permalink()));
?>