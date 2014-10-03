<?php
/*
Plugin Name: Advanced Custom Fields: 2-Way Post Relation
Plugin URI: https://github.com/hereswhatidid/acf-2way-pr/
Description: Creates an extended version of the Related Post field type that is bidirectional.
Version: 1.0.3
Author: Gabe Shackle
Author URI: http://hereswhatidid.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// 1. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_2waypr( $version ) {
	
	include_once('acf-2way-pr-v5.php');
	
}

add_action('acf/include_field_types', 'include_field_types_2waypr');


// 2. Include field type for ACF4
function register_fields_2waypr() {
	
	include_once('acf-2way-pr-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_2waypr');