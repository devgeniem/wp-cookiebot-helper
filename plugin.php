<?php
/**
 * Plugin Name: Cookiebot Helper
 * Plugin URI: https://github.com/devgeniem/wp-cookiebot-helper
 * Description: Helper plugin for handling Cookiebot consent states
 * Version: 1.0.0
 * Requires PHP: 7.0
 * Author: Geniem Oy / Hermanni Piirainen
 * Author URI: https://geniem.com
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-cookiebot-helper
 * Domain Path: /languages
 */

use Geniem\Cookiebot\CookiebotPlugin;

// Check if Composer has been initialized in this directory.
// Otherwise we just use global composer autoloading.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Get the plugin version.
$plugin_data    = get_file_data( __FILE__, [ 'Version' => 'Version' ], 'plugin' );
$plugin_version = $plugin_data['Version'];

$plugin_path = __DIR__;

// Initialize the plugin.
CookiebotPlugin::init( $plugin_version, $plugin_path );

if ( ! function_exists( 'cookiebot_helper' ) ) {
    /**
     * Get the Cookiebot Helper plugin instance.
     *
     * @return CookiebotPlugin
     */
    function cookiebot_helper() : CookiebotPlugin {
        return CookiebotPlugin::plugin();
    }
}
