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

function bootstrap() {
	add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );
}

function register_settings() {
	if ( defined( 'HM_DEV' ) && HM_DEV ) {
		register_setting( 'general', 'hm-basic-auth' );

		add_settings_field(
			'hm-basic-auth',
			__( 'Disable Basic Authentication', 'hm-basic-auth' ),
			__NAMESPACE__ . '\\basic_auth_setting_callback',
			'general',
			'default',
			[ 'label_for' => 'hm-basic-auth' ]
		);
	}
}

function basic_auth_setting_callback() {
	$checked = get_option( 'hm-basic-auth' );
	?>
	<input type="checkbox" name="hm-basic-auth" value="1" <?php checked( $checked, 1 ); ?> />
	<span class="description">
		<?php esc_html_e( 'If checked, this will disable the Basic Authentication for this environment.', 'hm-basic-auth' ); ?>
	</span>
	<?php
}
