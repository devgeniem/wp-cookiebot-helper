<?php
/**
 * This class handles cookie functionalities.
 */

namespace Geniem\Cookiebot;

use Geniem\Cookiebot\Utils\ConsentType;
use Geniem\Cookiebot\Utils\CookieState;

/**
 * Class CookieHandler.
 *
 * @package Geniem\Cookiebot
 */
class CookieHandler {

    /**
     * Define the Cookiebot cookie name.
     */
    public const COOKIE_NAME = 'CookieConsent';

    /**
     * Store the current states of consent.
     *
     * @var array
     */
    private $states = [];

    /**
     * Store the cookie content.
     *
     * @var array|null
     */
    private $cookie;

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct() {
        if ( isset( $_COOKIE[ self::COOKIE_NAME ] ) ) {
            $this->cookie = $_COOKIE[ self::COOKIE_NAME ];
        }

        $this->parse_states();
    }

    /**
     * Parse the states from cookie content.
     *
     * @return void
     */
    private function parse_states() {

        // Necessary cookies are always allowed, so add that state in any case.
        $this->add_state( ConsentType::NECESSARY );

        // Bail early if cookie is not set.
        if ( empty( $this->cookie ) ) {
            return;
        }

        switch ( $this->cookie ) {
            case CookieState::NOT_ACCEPTED:
                // User has not accepted cookies, don't set further consents.
                break;
            case CookieState::NOT_APPLICABLE:
                // Cookie consent is not required, so set all consents.
                foreach ( ConsentType::CHECKABLE_TYPES as $type ) {
                    $this->add_state( $type );
                }
                break;
            default:
                // Read current user consent in encoded JavaScript format
                $consent = self::parse_cookie( $this->cookie );

                foreach ( ConsentType::CHECKABLE_TYPES as $type ) {
                    if ( isset( $consent->$type ) && filter_var( $consent->$type, FILTER_VALIDATE_BOOLEAN ) ) {
                        $this->add_state( $type );
                    }
                }
        }
    }

    /**
     * Add state to $states.
     *
     * @param string $state The state name.
     * @return void
     */
    private function add_state( $state ) {
        if ( ! in_array( $state, $this->states, true ) ) {
            $this->states[] = $state;
        }
    }

    /**
     * Check if certain consent has been given.
     *
     * @param string $state The consent type.
     * @return boolean
     */
    public function is_cookie_state_accepted( $state ) {
        return in_array( $state, $this->states, true );
    }

    /**
     * Parse the cookie string to JSON-decoded array.
     *
     * @param string $cookie The cookie string.
     * @return array
     */
    private static function parse_cookie( $cookie ) {
        $json = preg_replace(
            '/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/', ':"$1"$2',
            preg_replace(
                '/([{\[,])\s*([a-zA-Z0-9_]+?):/', '$1"$2":',
                str_replace( "'", '"', stripslashes( $cookie ) )
            )
        );

        return json_decode( $json );
    }
}
