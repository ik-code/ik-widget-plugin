<?php

use Inc\Api\Widgets\IkWidget;

class IKWidgetPlugin {

	/**
	 * IKWidgetPlugin constructor.
	 */
	function __construct() {
		add_action( 'widgets_init', array($this, 'start_widget') );
	}

	/**
	 * Start_widget
	 */
	function start_widget(){
		$new_ik_widget = new IkWidget();
		$new_ik_widget->widgetInit();
	}

	/**
	 * Something to do by activation hook
	 */
	function activate() {
		//flush rewrite rules
	}

	/**
	 * Something to do by deactivation hook
	 */
	function deactivate() {
		//flush rewrite rules
	}
}

if ( class_exists( 'IKWidgetPlugin' ) ) {
	$ik_widget_plugin = new IKWidgetPlugin();
}

//activation
register_activation_hook( __FILE__, array( $ik_widget_plugin, 'activate' ) );
//deactivation
register_deactivation_hook( __FILE__, array( $ik_widget_plugin, 'deactivate' ) );
