<?php
/**
 * This file initializes all plugin functionalities.
 */

namespace Geniem\Cookiebot;

use Geniem\Cookiebot\Handlers;

/**
 * Class CookiebotPlugin
 *
 * @package Geniem\Cookiebot
 */
final class CookiebotPlugin {

    /**
     * Holds the singleton.
     *
     * @var CookiebotPlugin
     */
    protected static $instance;

    /**
     * Current plugin version.
     *
     * @var string
     */
    protected $version = '';

    /**
     * Get the instance.
     *
     * @return CookiebotPlugin
     */
    public static function get_instance() : CookiebotPlugin {
        return self::$instance;
    }

    /**
     * The plugin directory path.
     *
     * @var string
     */
    protected $plugin_path = '';

    /**
     * The plugin root uri without trailing slash.
     *
     * @var string
     */
    protected $plugin_uri = '';

    /**
     * The cookie handler instance.
     *
     * @var Cookie
     */
    protected $cookie_handler;

    /**
     * The handlers for different content.
     *
     * @var array
     */
    protected $handlers = [];

    /**
     * Get the version.
     *
     * @return string
     */
    public function get_version(): string {
        return $this->version;
    }

    /**
     * Get the plugin directory path.
     *
     * @return string
     */
    public function get_plugin_path() : string {
        return $this->plugin_path;
    }

    /**
     * Get the plugin directory uri.
     *
     * @return string
     */
    public function get_plugin_uri() : string {
        return $this->plugin_uri;
    }

    /**
     * Initialize the plugin by creating the singleton.
     *
     * @param string $version     The current plugin version.
     * @param string $plugin_path The plugin path.
     */
    public static function init( $version, $plugin_path ) {
        if ( empty( static::$instance ) ) {
            static::$instance = new self( $version, $plugin_path );
        }
        static::$instance->hooks();
        static::$instance->init_handlers();
    }

    /**
     * Initialize the plugin handlers.
     *
     * @return void
     */
    protected function init_handlers() {

        /*
            Uncomment the line below when parsing the consent cookie server-side is needed.
            See README > Notes about caching for more information.
        */
        // $this->cookie_handler = new CookieHandler();

        $this->handlers = [
            Handlers\YouTube::class,
        ];

        foreach ( $this->handlers as $handler_class ) {
            if ( class_exists( $handler_class ) ) {
                new $handler_class();
            }
        }
    }

    /**
     * Get the plugin instance.
     *
     * @return CookiebotPlugin
     */
    public static function plugin() {
        return static::$instance;
    }

    /**
     * Initialize the plugin functionalities.
     *
     * @param string $version     The current plugin version.
     * @param string $plugin_path The plugin path.
     */
    protected function __construct( $version, $plugin_path ) {
        $this->version     = $version;
        $this->plugin_path = $plugin_path;
        $this->plugin_uri  = plugin_dir_url( $plugin_path ) . basename( $this->plugin_path );
    }

    /**
     * Add plugin hooks and filters.
     */
    protected function hooks() {
        \add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
        \add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ] );
    }

    /**
     * Load plugin textdomain.
     *
     * @return void
     */
    public function load_textdomain() {
        \load_plugin_textdomain( 'wp-cookiebot-helper', false, basename( $this->get_plugin_path() ) . '/languages' );
    }

    /**
     * Enqueue public side scripts if they exist.
     */
    public function enqueue_public_scripts() {
        // Get file modification times to enable more dynamic versioning.
        $css_mod_time = file_exists( $this->plugin_path . '/assets/dist/main.css' ) ?
            filemtime( $this->plugin_path . '/assets/dist/main.css' ) : $this->version;
        $js_mod_time  = file_exists( $this->plugin_path . '/assets/dist/main.js' ) ?
            filemtime( $this->plugin_path . '/assets/dist/main.js' ) : $this->version;

        if ( file_exists( $this->plugin_path . '/assets/dist/main.css' ) ) {
            wp_enqueue_style(
                'cookiebot-helper-public-css',
                $this->plugin_uri . '/assets/dist/main.css',
                [],
                $css_mod_time,
                'all'
            );
        }

        if ( file_exists( $this->plugin_path . '/assets/dist/main.js' ) ) {
            wp_enqueue_script(
                'cookiebot-helper-public-js',
                $this->plugin_uri . '/assets/dist/main.js',
                [],
                $js_mod_time
            );
        }
    }
}
