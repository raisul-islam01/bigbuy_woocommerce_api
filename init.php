<?php
/*
 * Plugin Name:       Rangement api
 * Plugin URI:        https://rangement.api.com
 * Description:       Rangement Api
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Imjol
 * Author URI:        https://imjol.com/

*/

// Define plugin path
if ( !defined( 'PLUGIN_PATH' ) ) {
    define( 'PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

// Define plugin url
if ( !defined( 'PLUGIN_URI' ) ) {
    define( 'PLUGIN_URI', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

// Register the activation hook
register_activation_hook( __FILE__, 'rangement_db_products_table_create' );
// Register the deactivation hook
register_deactivation_hook( __FILE__, 'rangement_db_products_table_remove' );

//category register
// Register the activation hook
register_activation_hook( __FILE__, 'rangement_db_category_table_create' );
// Register the deactivation hook
register_deactivation_hook( __FILE__, 'rangement_db_category_table_remove' );

//sync table require 
require_once PLUGIN_PATH . '/init/sync_proudcts_db.php';
//sync product shortcode
require_once PLUGIN_PATH . '/init/sync_products_shortcode.php';

require_once PLUGIN_PATH . '/init/sync_category_db.php';

require_once PLUGIN_PATH . '/init/sync_category_shortcode.php';