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
 * Register the basic auth setting and the new settings field, but only if we're in a dev environment.
 * We don't want basic auth in production.
 */
function register_settings() {
	if ( defined( 'HM_DEV' ) && HM_DEV ) {
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
	$checked = get_option( 'hm-basic-auth' );
	?>
	<input type="checkbox" name="hm-basic-auth" value="1" <?php checked( $checked, 1 ); ?> />
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
