<?php
/**
 * Main unit test suite.
 *
 * @package HM\BasicAuth
 */

namespace HM\BasicAuth;

/**
 * Main testing suite for HMBasicAuth plugin.
 */
class Test_Main extends \WP_UnitTestCase {
	/**
	 * Check a variety of conditions that would effect whether is_development_environment retuns true or false.
	 */
	public function test_is_development_environment() {
		// Start by assuming we're not in a dev environment.
		$this->assertFalse(
			is_development_environment()
		);

		// If we define the ENV_TYPE as 'production' it should still be false.
		define( 'HM_ENV_TYPE', 'production' );
		$this->assertFalse(
			is_development_environment()
		);

		// Define HM_DEV so this becomes true.
		define( 'HM_DEV', true );
		$this->assertTrue(
			is_development_environment()
		);

		// But false if we're running WP_CLI, cron or AJAX.
		define( 'WP_CLI', true );
		define( 'DOING_CRON', true );
		define( 'DOING_AJAX', true );

		$this->assertFalse(
			is_development_environment()
		);

	}

	/**
	 * Test that the option in the admin toggles and overrides the environment defaults when set.
	 */
	public function test_basic_auth_setting_callback() {
		// Start by outputting the setting. It should be _unchecked_ because of the constants set in the previous test.
		ob_start();
		basic_auth_setting_callback();
		$output_before  = ob_get_clean();
		$trimmed_output = trim( str_replace( [ "\r", "\n" ], '', $output_before ) );

		// Test that the checkbox input does not include the "checked" attribute.
		$this->assertStringStartsWith(
			'<input type="checkbox" name="hm-basic-auth" value="on"  />',
			$trimmed_output
		);

		// Now we'll override the environment settings by turning the option "on".
		update_option( 'hm-basic-auth', 'on' );

		ob_start();
		basic_auth_setting_callback();
		$output_after   = ob_get_clean();
		$trimmed_output = trim( str_replace( [ "\r", "\n" ], '', $output_after ) );

		// Test that the checkbox input _does_ include the "checked" attribute.
		$this->assertStringStartsWith(
			'<input type="checkbox" name="hm-basic-auth" value="on"  checked=\'checked\' />',
			$trimmed_output
		);
	}

	/**
	 * Test the sanitization callback which takes any empty or falsy value and returns "off", and returns "on" for any other value.
	 */
	public function test_basic_auth_sanitization_callback() {
		$this->assertSame(
			basic_auth_sanitization_callback( null ),
			'off'
		);

		$this->assertSame(
			basic_auth_sanitization_callback( false ),
			'off'
		);

		$this->assertSame(
			basic_auth_sanitization_callback( '' ),
			'off'
		);

		$this->assertSame(
			basic_auth_sanitization_callback( true ),
			'on'
		);
	}
}
