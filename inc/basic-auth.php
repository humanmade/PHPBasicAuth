<?php
/**
 * Plugin Name: HM Basic Auth
 * Description: Basic PHP authentication for HM Dev and Staging environments.
 * Author: Human Made Limited
 * Author URI: https://humanmade.com
 * Version: 1.0
 *
 * @package HM\BasicAuth
 */

namespace HM\BasicAuth;

/**
 * Require PHP Basic authentication.
 *
 * Basic auth username and password should be set wherever constants for the site are defined.
 */
function require_auth() {
	$override_basic_auth = get_option( 'hm-basic-auth' );

	// Bail early if we're overriding.
	if ( '1' === $override_basic_auth ) {
		return;
	}

	// Check for a basic auth user and password.
	if ( defined( 'HM_BASIC_AUTH_PW' ) && defined( 'HM_BASIC_AUTH_USER' ) ) {
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );

		$has_supplied_credentials = ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] );
		$is_not_authenticated     = (
			! $has_supplied_credentials ||
			$_SERVER['PHP_AUTH_USER'] !== HM_BASIC_AUTH_USER ||
			$_SERVER['PHP_AUTH_PW'] !== HM_BASIC_AUTH_PW
		);

		if ( $is_not_authenticated ) {
			header( 'HTTP/1.1 401 Authorization Required' );
			header( 'WWW-Authenticate: Basic realm="Access denied"' );
			exit;
		}
	}
}
