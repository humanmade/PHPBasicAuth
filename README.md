<img src="https://humanmade.com/content/themes/humanmade/lib/hm-pattern-library/assets/images/logos/logo-red.svg" width="100" alt="Human Made Logo" />

# PHP Basic Auth
Basic PHP authentication for Human Made Dev and Staging environments.


## Installation & Setup
The composer file is set up to assume you want to install this package with other WordPress must-use vendor plugins. These setup instructions assume that all your composer-required must-use plugins are stored in a main `/mu-plugins/vendor` directory and that you are using a `loader.php` file to require them. You may need to adjust the configuration if your environment is different.

### Step 1
Install the plugin via `composer`.

```bash
composer require humanmade/PHPBasicAuth
```

### Step 2
Add `'vendor/php-basic-auth/0_basic-auth.php'` to the array of must-use plugins in the `loader.php` file in the root of your `/mu-plugins` directory. Make sure it is the _first_ item in the array.

The final result should look something like this:

```php
<?php
/**
 * Plugin Name: HM MU Plugin Loader
 * Description: Loads the MU plugins required to run the site
 * Author: Human Made Limited
 * Author URI: http://hmn.md/
 * Version: 1.0
 *
 * @package PlayStation
 */

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

// Plugins to be loaded for any site.
$global_mu_plugins = [
	'vendor/php-basic-auth/0_basic-auth.php',
	/* ... other must-use plugins here ... */
];
```

### Step 3
Define a `HM_BASIC_AUTH_USER` and `HM_BASIC_AUTH_PW` wherever constants are defined in your project. This could be your main `wp-config.php` file or a separate `.config/constants.php` file. 

**Note:** The constant declarations _must_ be inside a `HM_DEV` environment check, otherwise the basic authentication will be loaded on all environments. You may also want to disable basic authentication on local environments. 

Your constant declarations should look something like this:

```php
// Check if we're in a dev environment but not local.
if (
	// HM_DEV is defined and true.
	( defined( 'HM_DEV' ) && HM_DEV ) &&
	// HM_LOCAL_DEV is either undefined or false.
	( ! defined( 'HM_LOCAL_DEV' ) || defined( 'HM_LOCAL_DEV' ) && ! HM_LOCAL_DEV )
) {
	// Set Basic Auth user and password for dev environments.
	define( 'HM_BASIC_AUTH_USER', 'myusername' );
	define( 'HM_BASIC_AUTH_PW', 'mypassword' );
}
```

### Step 4 (optional)
If you do not want to load the basic authentication check on local environments, and you have not already defined `HM_LOCAL_DEV` in your `wp-config-local.php` file, you should do that now.

```php
/**
 * Set the environment to local dev.
 */
defined( 'HM_LOCAL_DEV' ) or define( 'HM_LOCAL_DEV', true );
```

You should also add these lines to your `wp-config-local.sample.php`.

## Credits

Created by Human Made to force authentication to view development and staging environments while still allowing those environments to be viewed in a logged-out state.

Maintained by [Chris Reynolds](https://github.com/jazzsequence).

---------------------

Made with ❤️ by [Human Made](https://humanmade.com)