<?php
/**
 * Utility class for defining constants for different consent types.
 */

namespace Geniem\Cookiebot\Utils;

/**
 * Class ConsentType.
 *
 * @package Geniem\Cookiebot
 */
final class ConsentType {
    const NECESSARY   = 'necessary';
    const PREFERENCES = 'preferences';
    const STATISTICS  = 'statistics';
    const MARKETING   = 'marketing';

    /**
     * The consent type translations.
     *
     * @var array
     */
    protected static $translations = [];

    /**
     * All consent types.
     */
    const TYPES = [
        self::NECESSARY,
        self::PREFERENCES,
        self::STATISTICS,
        self::MARKETING,
    ];

    /**
     * The types of consents to check.
     */
    const CHECKABLE_TYPES = [
        self::PREFERENCES,
        self::STATISTICS,
        self::MARKETING,
    ];

    /**
     * Get consent type translations.
     *
     * @return array
     */
    public static function get_translations() {
        if ( empty( static::$translations ) ) {
            static::$translations = [
                self::NECESSARY   => __( 'necessary cookies', 'wp-cookiebot-helper' ),
                self::PREFERENCES => __( 'preference cookies', 'wp-cookiebot-helper' ),
                self::STATISTICS  => __( 'statistic cookies', 'wp-cookiebot-helper' ),
                self::MARKETING   => __( 'marketing cookies', 'wp-cookiebot-helper' ),
            ];
        }

        return static::$translations;
    }

    /**
     * Get single consent type translation.
     *
     * @param string $type Consent type.
     * @return string
     */
    public static function get_translation( $type ) {
        $translations = static::get_translations();
        return isset( $translations[ $type ] ) ? $translations[ $type ] : '';
    }
}
