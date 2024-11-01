<?php 
  // determine if we're answering an AJAX call or NO 
  global $is_ajax; $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
  
  // require the useful HTML parsing library 'SimpleHTMLDOM'
  include(compat_get_plugin_dir( 'wipad' )."/vendor/simple_html_dom.php");
  $html = new simple_html_dom();
  
  // setting a resource base url variable 
  $resource_base_url = compat_get_plugin_url( 'wipad' )."/themes/sencha/resources";
  
  // if this belongs from an AJAX request reload only the post list
  if($is_ajax){
    include(compat_get_plugin_dir('wipad')."/themes/sencha/_post_list.js.php");
    exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php $str = bnc_get_header_title(); echo stripslashes($str); ?></title>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link <?php if (bnc_is_flat_icon_enabled()) { echo 'rel="apple-touch-icon-precomposed"'; } else { echo 'rel="apple-touch-icon"';} ?> href="<?php echo bnc_get_title_image(); ?>" />

	 <!-- Ext Touch CSS -->
	 <link rel="stylesheet" href="<?php echo compat_get_plugin_url( 'wipad' ); ?>/themes/sencha/resources/css/ext-touch.css" type="text/css">
	 
	 <!-- Application CSS -->
	 <style>
	  <?php include( compat_get_plugin_dir( 'wipad' )."/themes/sencha/sencha.css.php");  ?>
	 </style>
	 
	 <!-- Ext Touch JS -->
	 <script type="text/javascript" src="<?php echo compat_get_plugin_url( 'wipad' ); ?>/themes/sencha/ext-touch-debug.js"> </script>

	 <!-- Application JS -->
	 <script type="text/javascript"> 
	  <?php include( compat_get_plugin_dir( 'wipad' )."/themes/sencha/index.js.php");  ?>
	 </script>

</head>
<body> </body>
</html>