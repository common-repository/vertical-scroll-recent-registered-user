<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('vsrru_title');
delete_option('vsrru_dis_date');
delete_option('vsrru_dis_border');
delete_option('vsrru_dis_image');
delete_option('vsrru_select_num_user');
delete_option('vsrru_dis_num_user');
 
// for site options in Multisite
delete_site_option('vsrru_title');
delete_site_option('vsrru_dis_date');
delete_site_option('vsrru_dis_border');
delete_site_option('vsrru_dis_image');
delete_site_option('vsrru_select_num_user');
delete_site_option('vsrru_dis_num_user');