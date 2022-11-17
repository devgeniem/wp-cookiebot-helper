<?php
/**
 * Utility functions for handling markup-related stuff.
 */

namespace Geniem\Cookiebot\Utils;

/**
 * Class Markup
 *
 * @package Geniem\Cookiebot
 */
class Markup {

    /**
     * Return markup for wrapper start.
     *
     * @param string $state The state type.
     * @return string
     */
    public static function get_wrapper_start( $state = '' ) {
        $class = "cookieconsent-optout-$state";
        return '<div class="cookiebot-helper cookiebot-helper--hidden ' . \esc_attr( $class ) . '"><div class="cookiebot-helper__wrapper">';
    }

    /**
     * Return markup for wrapper end.
     *
     * @return string
     */
    public static function get_wrapper_end() {
        return '</div></div>';
    }

    /**
     * Return a placeholder with a link to renew Cookiebot consent.
     *
     * @param string $consent_type The consent type.
     * @return string
     */
    public static function get_renew_placeholder( $consent_type ) {
        $renew_url   = 'javascript:Cookiebot.renew();';
        $placeholder = sprintf(
            /* translators: 1: The URL to renew cookie consent 2: The consent type */
            __( 'Please <a href="%1$s" class="cookiebot-helper__link">accept %2$s</a> to watch the video.', 'wp-cookiebot-helper' ),
            $renew_url,
            $consent_type,
        );
        return $placeholder;
    }

    /**
     * Return YouTube placeholder including video image and link to the video.
     *
     * @param array  $embed_url_parts Array of parsed URL parts of the embed src.
     * @param string $original_url The original URL that was embedded.
     * @return string
     */
    public static function get_youtube_thumbnail_placeholder( $embed_url_parts, $original_url ) {
        $path       = $embed_url_parts['path'];
        $path_parts = explode( '/', $path );
        $video_id   = end( $path_parts );

        /**
         * Image size for YouTube thumbnail.
         *
         * Choose the best one for your theme.
         * Valid sizes are (at least):
         *     0, 1, 2, 3, default, hqdefault,
         *     mqdefault, sddefault, maxresdefault.
         */
        $thumbnail_size = \apply_filters( 'cookiebot_helper_youtube_image_size', 'maxresdefault' );
        $thumbnail_url  = "https://img.youtube.com/vi/$video_id/{$thumbnail_size}.jpg";
        $thumbnail      = '<img data-cookieconsent="ignore" src="' . esc_url( $thumbnail_url ) . '" class="cookiebot-helper-placeholder__thumbnail" alt="">';
        $content        = '<span class="cookiebot-helper-placeholder__content">' . __( 'Play video on YouTube (opens in new tab)', 'wp-cookiebot-helper' ) . '</span>';

        $placeholder  = '<div class="cookiebot-helper-placeholder__wrapper cookiebot-helper-placeholder__wrapper--youtube">';
        $placeholder .= '<a href="' . esc_url( $original_url ) . '" target="_blank" rel="noopener noreferrer" class="cookiebot-helper-placeholder__link">';
        $placeholder .= $thumbnail;
        $placeholder .= '</a>';
        $placeholder .= $content;
        $placeholder .= '</div>';

        return $placeholder;
    }
}
