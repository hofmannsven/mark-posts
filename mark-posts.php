<?php
/**
 * Plugin Name:     Mark Posts
 * Description:     Mark and highlight posts, pages and posts of custom post types within the posts overview.
 * Plugin URI:      https://wordpress.org/plugins/mark-posts
 * Version:         1.2.0
 * Author:          Michael Schoenrock, Sven Hofmann
 * Author URI:      https://www.halloecho.de
 * Contributor:     Sven Hofmann
 * Contributor URI: https://hofmannsven.com
 * License:         GPL-2.0+
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     mark-posts
 * GitHub URI:      https://github.com/hofmannsven/mark-posts
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * plugin version
 *
 */
if ( ! defined( 'WP_MARK_POSTS_VERSION' ) ) {
	define( 'WP_MARK_POSTS_VERSION', '1.2.0' );
}

/*
 * plugin dir path
 *
 */
if ( ! defined( 'WP_MARK_POSTS_PATH' ) ) {
	define( 'WP_MARK_POSTS_PATH', plugin_dir_path( __FILE__ ) );
}

/*
 * plugin's class file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-mark-posts.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Mark_Posts', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Mark_Posts', 'deactivate' ) );

/*
 * Load the plugin text domain for translation
 *
 */
function mark_posts_load_textdomain() {
	load_plugin_textdomain( 'mark-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'mark_posts_load_textdomain', 1 );

/*
 * Add action plugins_loaded
 *
 */
add_action( 'plugins_loaded', array( 'Mark_Posts', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-mark-posts-marker.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-mark-posts-admin.php' );
	add_action( 'plugins_loaded', array( 'Mark_Posts_Admin', 'get_instance' ) );

}
