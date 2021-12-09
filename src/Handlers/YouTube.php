<?php
/**
 * This class handles YouTube embed functionalities.
 */

namespace Geniem\Cookiebot\Handlers;

use Geniem\Cookiebot\Utils\ConsentType;
use Geniem\Cookiebot\Utils\Markup;

/**
 * Class YouTube.
 *
 * @package Geniem\Cookiebot
 */
class YouTube {

    const PLACEHOLDER_TYPE_RENEW = 'renew';
    const PLACEHOLDER_TYPE_IMAGE = 'image';

    /**
     * The consent type that is required to disable this handler.
     *
     * @var string
     */
    protected $opt_out_type;

    /**
     * The cookie handler instance.
     * 
     * Not in use currently as server-side parsing is not needed.
     *
     * @var CookieHandler
     */
    protected $cookie_handler;

    /**
     * Class constructor
     */
    public function __construct() {
        $this->opt_out_type = ConsentType::MARKETING;
        $this->hooks();
    }

    /**
     * Register hooks for this handler.
     *
     * @return void
     */
    protected function hooks() {
        \add_filter( 'embed_oembed_html', [ $this, 'add_placeholder' ], 10, 2 );
        \add_filter( 'acf/format_value/type=oembed', [ $this, 'format_acf_oembed' ], 20, 3 );
    }

    /**
     * Get the original video URL and add placeholder.
     *
     * @param mixed      $value The field value.
     * @param int|string $post_id The post ID.
     * @param array      $field The field array containing all settings.
     * @return string
     */
    public function format_acf_oembed( $value, $post_id, $field ) {
        if ( function_exists( 'get_field' ) ) {
            $url = \get_field( $field['name'], $post_id, false );
            return $this->add_placeholder( $value, $url );
        }

        return $value;
    }

    /**
     * Add placeholder markup for YouTube embeds.
     * The placeholder is hidden by Cookiebot if user has given consent.
     *
     * @param string $html The embed HTML.
     * @param string $url  The embed URL.
     * @return string
     */
    public function add_placeholder( $html, $url ) {

        // Parse the iframe src
        preg_match( '/src="([^"]+)"/', $html, $match );
        if ( ! isset( $match[1] ) ) {
            return $html;
        }

        $src           = $match[1];
        $url_parts     = \wp_parse_url( $src );
        $host          = $url_parts['host'];
        $allowed_hosts = [
            'youtube.com',
            'www.youtube.com',
        ];

        // Bail if not a YouTube embed
        if ( ! in_array( $host, $allowed_hosts, true ) ) {
            return $html;
        }

        $placeholder = Markup::get_wrapper_start( $this->opt_out_type );

        /**
         * Filter what type of placeholder should be used.
         *
         * 'renew' (default value) to show a link to change consent.
         * 'image' to show the video thumbnail and link to YouTube.
         *
         * @param $type The type of placeholder to use, see description.
         */
        $placeholder_type = \apply_filters( 'cookiebot_helper_youtube_placeholder_type', self::PLACEHOLDER_TYPE_RENEW );

        switch ( $placeholder_type ) {
            case self::PLACEHOLDER_TYPE_IMAGE:
                $placeholder = Markup::get_youtube_thumbnail_placeholder( $url_parts, $url );
                break;
            default:
                $consent_translation = ConsentType::get_translation( $this->opt_out_type );
                $placeholder         = Markup::get_renew_placeholder( $consent_translation );
        }

        /**
         * Filter the YouTube embed placeholder HTML string.
         *
         * @param string $html The placeholder HTML string.
         */
        $placeholder = \apply_filters( 'cookiebot_helper_youtube_placeholder_output', $placeholder );
        $placeholder = Markup::get_wrapper_start( $this->opt_out_type ) . $placeholder . Markup::get_wrapper_end();
        return $placeholder . $html;
    }
}
