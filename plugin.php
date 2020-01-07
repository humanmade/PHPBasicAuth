<?php
/**
 * Plugin Name: HM Basic Auth
 * Description: Basic PHP authentication for HM Dev and Staging environments.
 * Author: Human Made Limited
 * Author URI: https://humanmade.com
 * Version: 1.0.1
 *
 * @package HM\BasicAuth
 */

namespace HM\BasicAuth;

require_once( 'inc/namespace.php' );
require_once( 'inc/basic-auth.php' );

// Kick it off.
bootstrap();
