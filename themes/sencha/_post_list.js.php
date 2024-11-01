<?php 
$post_array = array();
if (have_posts()) : while (have_posts()) : the_post();  
  global $more;
  $more = 1;
  $the_hash    = json_encode(str_replace(get_bloginfo('wpurl')."/", "#/", get_permalink()));
  $the_title   = json_encode(get_the_title());
  $the_content = json_encode("<div class='x-htmlcontent the_content'>".apply_filters('the_content',get_the_content())."</div>");
  $background  = $resource_base_url."/img/classicbackground.jpg";
  $post_array[] = <<<POST
    { 
     text: $the_title,
     the_hash: $the_hash,
     card: new Ext.Panel({
        html: $the_content,
        scroll: 'vertical'
     })
   } 
POST;

endwhile; endif;

$html->load("<html><body>".get_next_posts_link()."</html></body>");
$next_link = $html->find('a');
$next_link = json_encode($next_link[0]->href);

if(!($next_link == 'null')){
  $post_array[] = <<<POST
     { 
      text:        "Show more posts &raquo;",
      load_source: $next_link
     } 
POST;
}


?>

<?php echo "[".implode(",",$post_array)."]"; ?>
