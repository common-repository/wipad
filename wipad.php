<?php
/*
  Plugin Name: WPtouch ~iPad~ Theme
  Description: A plugin that heavily relies on wptouch in order to create a theme for iPad.
  Author: Sandro Paganotti on behalf of Wave Factory 
  Version: 0.2
  Author URI: http://factory.wavegroup.it
   
	# Thanks to BraveNewCode 
	# whom plugin we use to all the kind of stuff (except theming :)
	# (http://www.wptouch.com/)
	
	# The code in this plugin is free software; you can redistribute the code aspects of
	# the plugin and/or modify the code under the terms of the GNU Lesser General
	# Public License as published by the Free Software Foundation; either
	# version 2.1 of the License, or (at your option) any later version.
	
	# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
	# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
	# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
	# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
	#
	# See the GNU lesser General Public License for more details.
*/

/* Enable sessions (for debug pourpose) */
session_start();

$wp_touch_filepath = dirname(__FILE__).'/../wptouch/wptouch.php';
$wp_touch_filepath_exists = file_exists($wp_touch_filepath);

/* Show an error message if wp-touch is not installed */
add_action( 'admin_notices', 'check_for_wptouch_presence');
function check_for_wptouch_presence(){
    global $wp_touch_filepath_exists;
    if(!$wp_touch_filepath_exists)
    echo '<div class="error fade" style="background-color:red;"><p>WiPad depends heavily on wp-touch; <a href="http://wordpress.org/extend/plugins/wptouch/">Install it</a> with version >= 1.9.16 (you can keep it deactivated if you prefer) in order to use this plugin</p></div>';
}

/* Return if wp-touch is not installed */
if(!$wp_touch_filepath_exists)
  return;

if(!class_exists('WPtouchPlugin'))
  require_once($wp_touch_filepath);

class WiPadPlugin extends WPtouchPlugin {
  
  /* In this stage force the view to the iPad style (if the user-agent is the iPad one) */
  function bnc_filter_iphone(){
    $this->desired_view = 'ipad';
  }	
  
  function detectAppleMobile($query = '') {
		$container = $_SERVER['HTTP_USER_AGENT'];
		$this->applemobile = false;
		$useragents = array('iPad');
		$devfile =  compat_get_plugin_dir( 'wptouch' ) . '/include/developer.mode';
		foreach ( $useragents as $useragent ) {
			if ( preg_match( "#$useragent#i", $container ) || file_exists( $devfile ) || $_SESSION['force'] == "1") {
				$this->applemobile = true;
			} 	
		}
		/* Set force to = 1 to test iPad behavior also on PC */ 
		if($_GET["force"] != null){
		  $_SESSION['force'] = $_GET["force"];
		  header('Location: '.get_bloginfo('wpurl'));
		  exit(0);
		}
	}
	
	function get_stylesheet( $stylesheet ) {
		return $this->applemobile ? 'sencha' : $stylesheet;
	}
		  
	function get_template( $template ) {
		$this->bnc_filter_iphone();
		return $this->applemobile ? 'sencha' : $template;
	}
		  
	function get_template_directory( $value ) {
		$theme_root = compat_get_plugin_dir( 'wipad' );
		return $this->applemobile ? $theme_root . '/themes' : $value;
	}
		  
	function theme_root( $path ) {
		$theme_root = compat_get_plugin_dir( 'wipad' );
		return $this->applemobile ? $theme_root . '/themes' : $path;
	}
		  
	function theme_root_uri( $url ) {
	  return $this->applemobile ? compat_get_plugin_url( 'wipad' ) . "/themes" : $url;
	}
}

global $wipad_plugin;
$wipad_plugin = new WiPadPlugin();


?>