<?php
/**
 * Plugin Name: WP Generate Passphrase
 * Plugin URI: http://www.binarytemplar.com/wp-generate-passphrase
 * Description: Change the default behavior of WordPress to generate passphrases, not passwords.
 * Version: 1.0
 * Author: Dave McHale
 * Author URI: http://www.binarytemplar.com
 * Text Domain: wp-generate-passphrase
 * License: GPL2+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'classes/wp-generate-passphrase.php' );
new WP_Generate_Passphrase( __FILE__ );
