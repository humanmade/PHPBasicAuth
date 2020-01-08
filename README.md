<img src="https://humanmade.com/content/themes/humanmade/lib/hm-pattern-library/assets/images/logos/logo-red.svg" width="100" alt="Human Made Logo" />

# PHP Basic Auth
[![GitHub release (latest by date)](https://img.shields.io/github/v/release/humanmade/PHPBasicAuth)](https://github.com/humanmade/PHPBasicAuth/releases) [![GitHub](https://img.shields.io/github/license/humanmade/PHPBasicAuth)](https://github.com/humanmade/PHPBasicAuth/blob/master/LICENSE)

Basic PHP authentication for Human Made Dev and Staging environments.


## Installation & Setup
The composer file is set up to assume you want to install this package with other WordPress must-use vendor plugins. These setup instructions assume that all your composer-required must-use plugins are stored in a main `/mu-plugins/vendor` directory and that you are using a `loader.php` file to require them. You may need to adjust the configuration if your environment is different.

After installation and setup, an option to override the default basic auth setting (detected by environment) will exist on the General settings page. This option allows you to disable the basic auth on dev or staging environments from the WordPress application. By default the option will detect the environment and be checked if no setting is saved.

![screenshot of new setting](https://p94.f3.n0.cdn.getcloudapp.com/items/P8uY1xqw/Screenshot+2020-01-07+15.55.50.png?v=93007848bc828da7bd3aa512553a1f17)

### Step 1
Install the plugin via `composer`.

```bash
composer require humanmade/PHPBasicAuth
```

### Step 2
Add `'vendor/php-basic-auth/plugin.php'` to the array of must-use plugins in the `loader.php` file in the root of your `/mu-plugins` directory. Make sure it is the _first_ item in the array.

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
 * @package HM
 */

if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

// Plugins to be loaded for any site.
$global_mu_plugins = [
	'vendor/php-basic-auth/plugin.php',
	/* ... other must-use plugins here ... */
];
```

### Step 3
Define a `HM_BASIC_AUTH_USER` and `HM_BASIC_AUTH_PW` wherever constants are defined in your project. This could be your main `wp-config.php` file or a separate `.config/constants.php` file. 

**Note:** While not required, it's best to check that you are in a development environment before defining `HM_BASIC_AUTH_USER` and `HM_BASIC_AUTH_PW` to prevent the constant declarations from being defined in all environments. This adds an additional layer of protection against basic auth accidentally being loaded in production.

You may also want to disable basic authentication on local environments. 

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

## Changelog

### 1.0.1
* Flipped the logic of the admin setting from checking to _disable_ basic authentication to checking to _enable_ basic authentication, and defaulting to environment-based settings.
* Added a `is_development_environment` function which includes an added check for `HM_ENV_TYPE` as well as arbitrary definitions that could be added by a filter.
* Updated "Basic Realm" to use the site name rather than "Access Denied"
* Disabled basic auth if any of the following WordPress constants are defined and true: `WP_CLI`, `DOING_AJAX`, `DOING_CRON`.

### 1.0
* Initial release

## Credits

Created by Human Made to force authentication to view development and staging environments while still allowing those environments to be viewed in a logged-out state.

Maintained by [Chris Reynolds](https://github.com/jazzsequence).

---------------------

Made with ❤️ by [Human Made](https://humanmade.com)