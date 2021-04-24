<?php
/**
Plugin Name: IK Widget Plugin
Plugin URI: http://wp.medi-com.info
Description:Adds a new widget. The widget displays a list of users of the site sorted by the number of comments.
Version: 1.0.0
Author: Ihor Khaletskyi
Author URI: http://wp.medi-com.info
License: GPLv2 or later
Text Domain: ik-widget-plugin
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

defined( 'ABSPATH' ) or die( 'Hey, you can\t access this file!' );

if(file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' )){
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( ! defined( 'IK_PLUGIN_DIR' ) ) {
	define( 'IK_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/ik-widget-plugin' ); // Full path, no trailing slash.
}
require_once IK_PLUGIN_DIR . '/Inc/IKWidgetPlugin.php';
