<?php
/**
 * Basic Authentication
 *
 * Handles the WordPress hooks and callbacks for the admin override of the basic authentication as defined by the environment settings.
 *
 * @package HM\BasicAuth
 */

namespace HM\BasicAuth;

/**
 * Kick everything off.
 */
function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );
}

/**
 * Checks if we're in a development environment.
 *
 * @return bool Returns true if we are in a dev or staging environment, false if not.
 */
function is_development_environment() : bool {
	$hm_dev    = defined( 'HM_DEV' ) && HM_DEV;
	$altis_dev = defined( 'HM_ENV_TYPE' ) && in_array( HM_ENV_TYPE, [ 'development', 'staging' ] );

	/**
	 * Allow our environments to be filtered outside of the plugin.
	 *
	 * @param bool
	 */
	$other_dev = apply_filters( 'hmauth_filter_dev_env', false );

	// If any of the environment checks are true, we're in a dev environment.
	if ( $hm_dev || $altis_dev || $other_dev ) {
		return true;
	}

	return false;
}

/**
 * Register the basic auth setting and the new settings field, but only if we're in a dev environment.
 * We don't want basic auth in production.
 */
function register_settings() {
	if ( is_development_environment() ) {
		register_setting( 'general', 'hm-basic-auth', [ 'sanitize_callback' => __NAMESPACE__ . '\\basic_auth_sanitization_callback' ] );

		add_settings_field(
			'hm-basic-auth',
			__( 'Enable Basic Authentication', 'hm-basic-auth' ),
			__NAMESPACE__ . '\\basic_auth_setting_callback',
			'general',
			'default',
			[ 'label_for' => 'hm-basic-auth' ]
		);
	}
}

/**
 * The basic auth override setting.
 */
function basic_auth_setting_callback() {
	$checked = get_option( 'hm-basic-auth' ) ?: is_development_environment();
	?>
	<input type="checkbox" name="hm-basic-auth" value="on" <?php checked( $checked, 'on' ); ?> />
	<span class="description">
		<?php esc_html_e( 'When checked, Basic Authentication will be required for this environment. The default is for this to be active on dev and staging environments.', 'hm-basic-auth' ); ?>
	</span>
	<?php
}

/**
 * Sanitization callback for the hm-basic-auth setting.
 *
 * Explicitly stores "off" if the setting has been turned off, and "on" if it has been activated.
 *
 * @param string $value "off" or "on" based on whether the checkbox was ticked.
 */
function basic_auth_sanitization_callback( $value ) : string {
	if ( empty( $value ) ) {
		return 'off';
	}

	return 'on';
}
