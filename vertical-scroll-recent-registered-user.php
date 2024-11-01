<?php
/*
Plugin Name: Vertical scroll recent registered user
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-registered-user/
Description: Vertical scroll recent registered user wordpress plugin create the scroller in the widget with recently registered user avatar, username and date.
Author: Gopi Ramasamy
Author URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-registered-user/
Donate link: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-registered-user/
Version: 9.1
Tags: Vertical, scroll, recent, registered, user
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vertical-scroll-recent-registered-user
Domain Path: /languages
*/

function vsrru() 
{
	$atts = array();
	$atts["date"] = get_option('vsrru_dis_date');
	$atts["border"] = get_option('vsrru_dis_border');
	$atts["image"] = get_option('vsrru_dis_image');
	$atts["limit"] = get_option('vsrru_select_num_user');
	$atts["display"] = get_option('vsrru_dis_num_user');
	echo vsrru_shortcode($atts);
}

function vsrru_shortcode($atts) 
{
	//[vertical-scroll-user date="YES" border="YES" image="YES" limit="3" display="2"]
	if ( ! is_array( $atts ) ) {
		return '';
	}
	$vsrru_dates =  $atts['date'];
	$vsrru_border =  $atts['border'];
	$vsrru_image =  $atts['image'];
	$vsrru_limit =  $atts['limit'];
	$vsrru_display =  $atts['display'];
	
	global $wpdb;
	global $blog_id;
	$blog_prefix = $blog_id."_";
		
	if($blog_id == 1) {
		$blog_prefix = "";
	}
		
	$num_user = $vsrru_limit;
	$dis_num_user = $vsrru_display;
	if(!is_numeric($num_user)) {
		$num_user = 5;
	} 
	if(!is_numeric($dis_num_user)) {
		$dis_num_user = 5;
	}
	
	$vsru = "";
	$vsrruhtml = "";
	$vsrru_x = "";
	$vsrru_data = $wpdb->get_results("select ID,display_name,user_registered from ". $wpdb->base_prefix.$blog_prefix . "users ORDER BY user_registered desc limit 0, $num_user");
	
	if($vsrru_border == "YES") {
		$vsru .= '<style type="text/css">';
		$vsru .= ' .vsrru-regimage img {';
		$vsru .= ' float: left;';
		$vsru .= ' border: 1px solid #CCCCCC;';
		$vsru .= ' vertical-align:bottom;';
		$vsru .= ' padding: 3px ;';
		$vsru .= ' };';
		$vsru .= '</style>';
    } 
	else {
		$vsru .= '<style type="text/css">';
		$vsru .= ' .vsrru-regimage img {';
		$vsru .= ' float: left;';
		$vsru .= ' vertical-align:bottom;';
		$vsru .= ' padding: 3px;';
		$vsru .= ' };';
		$vsru .= '</style>';
    } 
	
	if ( ! empty($vsrru_data) ) 
	{
		$vsrru_count = 0;
		foreach ( $vsrru_data as $vsrru_data ) 
		{
			$vsrru_name = $vsrru_data->display_name;
			$vsrru_date = mysql2date(get_option('date_format'), $vsrru_data->user_registered);
			$avatar = get_avatar( $vsrru_data->ID, 32 );
			
			if($vsrru_dates == 'YES') { 
				$vsrru_date = "$vsrru_date";  
			}
			else {
				$vsrru_date = "";
			}
			
			if($vsrru_image == 'YES') { 
				$avatar = "$avatar";  
			}
			else {
				$avatar = "";
			}
			
			$vsrruhtml = $vsrruhtml . "<div class='vsrru_div' style='height:70px;padding:1px 0px 1px 0px;'>"; 
			$vsrruhtml = $vsrruhtml . "<span class='vsrru-regimage'>$avatar;</span>";
			$vsrruhtml = $vsrruhtml . "<span style='padding-left:4px;'><strong><?php echo $vsrru_name; ?>.</strong></span><br>";
			$vsrruhtml = $vsrruhtml . "<span style='padding-left:4px;font-size:9px;'><i>$vsrru_date</i></span>";
			$vsrruhtml = $vsrruhtml . "</div>";
			
			$avatar = esc_sql(trim(get_avatar( $vsrru_data->ID, 32 )));
			if($vsrru_image == 'YES') { 
				$avatar = "$avatar";  
			}
			else {
				$avatar = "";
			}
			
			$vsrru_x = $vsrru_x . "vsrru[$vsrru_count] = '<div class=\'vsrru_div\' style=\'height:70px;padding:1px 0px 1px 0px;\'><span class=\'vsrru-regimage\'>$avatar</span><span style=\'padding-left:4px;\'><strong>$vsrru_name.</strong></span><br><span style=\'padding-left:4px;font-size:9px;\'><i>$vsrru_date</i></span></div>'; ";
			$vsrru_count++;
		}
		
		if($vsrru_count >= $dis_num_user) {
			$vsrru_count = $dis_num_user;
			$vsrru_height = 210;
		}
		else {
			$vsrru_count = $vsrru_count;
			$vsrru_height = ($vsrru_count*62);
		}
		
		
		$vsru .= '<div style="padding-top:8px;padding-bottom:8px;">';
			$vsru .= '<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 3px; height:' . $vsrru_height . 'px;" id="vsrruHolder">';
				$vsru .= $vsrruhtml;
			$vsru .= '</div>';
		$vsru .= '</div>';
		
		$vsru .= '<script type="text/javascript" src="' . plugins_url() . '/vertical-scroll-recent-registered-user/vertical-scroll-recent-registered-user.js"></script>';
		$vsru .= '<script type="text/javascript">';
		$vsru .= 'var vsrru = new Array();';
		$vsru .= "var objVSRRU = '';";
		$vsru .= "var vsrru_scrollPos  = '';";
		$vsru .= "var vsrru_numScrolls = '';";
		$vsru .= "var vsrru_heightOfElm = '70';";
		$vsru .= "var vsrru_numberOfElm = '" . $vsrru_count . "';";
		$vsru .= "var vsrru_scrollOn = 'true';";
		$vsru .= 'function createVSRRUScroll()'; 
		$vsru .= '{';
			$vsru .= $vsrru_x;
			$vsru .= "objVSRRU = document.getElementById('vsrruHolder');";
			$vsru .= "objVSRRU.style.height = (vsrru_numberOfElm * vsrru_heightOfElm) + 'px';";
			$vsru .= 'vsrruContent();';
		$vsru .= '}';
		$vsru .= '</script>';
		$vsru .= '<script type="text/javascript">';
		$vsru .= 'createVSRRUScroll();';
		$vsru .= '</script>';
	}
	else
	{
		$vsru .= "No data available!";
	}
	return $vsru;
}

function vsrru_install() 
{
	add_option('vsrru_title', "Recent Member");
	add_option('vsrru_dis_date', "YES");
	add_option('vsrru_dis_border', "YES");
	add_option('vsrru_dis_image', "YES");
	add_option('vsrru_select_num_user', "5");
	add_option('vsrru_dis_num_user', "5");
}

function vsrru_control() 
{
	$vsrru_title = get_option('vsrru_title');
	$vsrru_dis_date = get_option('vsrru_dis_date');
	$vsrru_dis_border = get_option('vsrru_dis_border');
	$vsrru_dis_image = get_option('vsrru_dis_image');
	$vsrru_select_num_user = get_option('vsrru_select_num_user');
	$vsrru_dis_num_user = get_option('vsrru_dis_num_user');
	
	if (isset($_POST['vsrru_submit'])) 
	{
		$vsrru_title 			= stripslashes(sanitize_text_field($_POST['vsrru_title']));
		$vsrru_dis_date 		= stripslashes(sanitize_text_field($_POST['vsrru_dis_date']));
		$vsrru_dis_border 		= stripslashes(sanitize_text_field($_POST['vsrru_dis_border']));
		$vsrru_dis_image 		= stripslashes(sanitize_text_field($_POST['vsrru_dis_image']));
		$vsrru_select_num_user 	= intval($_POST['vsrru_select_num_user']);
		$vsrru_dis_num_user 	= intval($_POST['vsrru_dis_num_user']);
		
		if($vsrru_dis_date != "YES" && $vsrru_dis_date != "NO") { $vsrru_dis_date = "YES"; }
		if($vsrru_dis_border != "YES" && $vsrru_dis_border != "NO") { $vsrru_dis_border = "YES"; }
		if($vsrru_dis_image != "YES" && $vsrru_dis_image != "NO") { $vsrru_dis_image = "YES"; }
		if(!is_numeric($vsrru_select_num_user) || $vsrru_select_num_user == 0) { $vsrru_select_num_user = 5; }
		if(!is_numeric($vsrru_dis_num_user) || $vsrru_dis_num_user == 0) { $vsrru_dis_num_user = 5; }
		
		update_option('vsrru_title', $vsrru_title );
		update_option('vsrru_dis_date', $vsrru_dis_date );
		update_option('vsrru_dis_border', $vsrru_dis_border );
		update_option('vsrru_dis_image', $vsrru_dis_image );
		update_option('vsrru_select_num_user', $vsrru_select_num_user );
		update_option('vsrru_dis_num_user', $vsrru_dis_num_user );
	}
	
	echo '<p>'.__('Title:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 200px;" type="text" value="';
	echo $vsrru_title . '" name="vsrru_title" id="vsrru_title" /></p>';
	
	echo '<p>'.__('Display date:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 100px;" type="text" value="';
	echo $vsrru_dis_date . '" name="vsrru_dis_date" id="vsrru_dis_date" /> (YES/NO)</p>';
	
	echo '<p>'.__('Display image border:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 100px;" type="text" value="';
	echo $vsrru_dis_border . '" name="vsrru_dis_border" id="vsrru_dis_border" /> (YES/NO)</p>';
	
	echo '<p>'.__('Display image:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 100px;" type="text" value="';
	echo $vsrru_dis_image . '" name="vsrru_dis_image" id="vsrru_dis_image" /> (YES/NO)</p>';
	
	echo '<p>'.__('Display number of user at the same time in scroll:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 100px;" type="text" value="';
	echo $vsrru_dis_num_user . '" name="vsrru_dis_num_user" id="vsrru_dis_num_user" /></p>';
	
	echo '<p>'.__('Enter max number of user to display:' , 'vertical-scroll-recent-registered-user').'<br><input  style="width: 100px;" type="text" value="';
	echo $vsrru_select_num_user . '" name="vsrru_select_num_user" id="vsrru_select_num_user" /></p>';
	
	echo '<input type="hidden" id="vsrru_submit" name="vsrru_submit" value="1" />';
	
	echo '<p>';
	_e('Check official website for more information', 'vertical-scroll-recent-registered-user');
	?> 
	<a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-registered-user/">
	<?php _e('click here', 'vertical-scroll-recent-registered-user'); ?></a></p><?php
}

function vsrru_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('vsrru_title');
	echo $after_title;
	vsrru();
	echo $after_widget;
}

function vsrru_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('vertical-scroll-recent-registered-user', 
				__('Vertical scroll recent registered user' , 'vertical-scroll-recent-registered-user'), 'vsrru_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('vertical-scroll-recent-registered-user', 
				__('Vertical scroll recent registered user' , 'vertical-scroll-recent-registered-user'), 'vsrru_control');
	} 
}

function vsrru_deactivation() 
{
	delete_option('vsrru_title');
	delete_option('vsrru_dis_date');
	delete_option('vsrru_dis_border');
	delete_option('vsrru_dis_image');
	delete_option('vsrru_dis_num_user');
	delete_option('vsrru_select_num_user');
}

function vsrru_textdomain() 
{
	  load_plugin_textdomain( 'vertical-scroll-recent-registered-user', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_shortcode('vertical-scroll-user', 'vsrru_shortcode');
add_action('plugins_loaded', 'vsrru_textdomain');
add_action("plugins_loaded", "vsrru_init");
register_activation_hook(__FILE__, 'vsrru_install');
register_deactivation_hook(__FILE__, 'vsrru_deactivation');
?>