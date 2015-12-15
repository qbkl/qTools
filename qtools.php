<?php
/*
Plugin Name:       qTools
Plugin URI:        https://github.com/qbkl/qtools
GitHub Plugin URI: https://github.com/qbkl/qtools
Description:       Custom plugin for QBKL Studio WordPress based products
Author:            QBKL Studio
Version:           1.0.0
Author URI:        http://qbkl.net
License:           GNU General Public License v2
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
Domain Path:       /lang
Text Domain:       qtools
*/


// Loading translations

add_action('plugins_loaded', 'qtools_load_textdomain');
function qtools_load_textdomain() {
	load_plugin_textdomain( 'qtools', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}


// Initiating qTools

$qtools = new qTools();

// qTools Class

class qTools {
	public function __construct() {
		
		// Loading dependencies
		$this->qTools_load_dependencies();
	}
	
	private function qTools_load_dependencies() {
	
		// Loading shorcodes
		require_once( plugin_dir_path(__FILE__) . 'inc/qtools-shortcodes.php' );

		// Loading widgets
		require_once( plugin_dir_path(__FILE__) . 'inc/qtools-widget-latest-posts.php' );
		require_once( plugin_dir_path(__FILE__) . 'inc/qtools-widget-latest-category.php' );
		require_once( plugin_dir_path(__FILE__) . 'inc/qtools-widget-same-author.php' );
		require_once( plugin_dir_path(__FILE__) . 'inc/qtools-widget-author-info.php' );
	}
	
	public function qTools_sanitize_checkbox( $value ) {
		$valid_array = array( 'on' );

		$sanitized_value = '';

		if ( in_array( strtolower( $value ), $valid_array ) ) {
			$sanitized_value = 'on';
		}
		
		return $sanitized_value;
	}
}
?>