<?php
/**
 * Utility class for defining constants for different cookie states.
 */

namespace Geniem\Cookiebot\Utils;

/**
 * Class CookieState.
 *
 * @package Geniem\Cookiebot
 */
final class CookieState {
    /**
     * User has not accepted cookies.
     */
    const NOT_ACCEPTED = '0';

    /**
     * User is not within region that requires consent.
     */
    const NOT_APPLICABLE = '-1';
}
