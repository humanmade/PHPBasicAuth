<?php
/**
 * Basic authentication
 *
 * Handles sending the actual PHP authentication headers if enabled or the environment requires them.
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

	if (
		// Bail if basic auth has been disabled...
		( ! $basic_auth || 'off' === $basic_auth ) ||
		// ...or if the development environment isn't defined or explicitly false.
		( ! is_development_environment() )
	) {
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
