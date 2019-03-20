<?php

/**
 * WP_Generate_Passphrase class
 *
 * Most of the work is done in here
 */
class WP_Generate_Passphrase {

	const MENU_SLUG = 'wp_generate_passphrase_settings';
	const CAPABILITY = 'manage_options';

	/**
	 * Stores 'wp-generate-passphrase/wp-generate-passphrase.php' typically
	 *
	 * @var string
	 */
	private $base_file_path;


	/**
	 * WP_Generate_Passphrase constructor.
	 *
	 * @param $path
	 */
	public function __construct( $path ) {

		// Set variable so the class knows how to reference the plugin
		$this->base_file_path = plugin_basename( $path );

		// Add link in admin area to plugin settings
		add_action( 'admin_menu', array( &$this, 'define_admin_link' ) );

		// This is what we're here for. Filter `wp_generate_password` with our custom functionality
		add_filter( 'random_password', array( 'WPGP_Worker', 'wp_generate_passphrase' ), 20 );

	}


	/**
	 * Add a menu
	 *
	 * @return void
	 */
	public function define_admin_link() {

		add_options_page( esc_html__( 'WP Generate Passphrase Settings', 'wp-generate-passphrase' ), esc_html__( 'WP Generate Passphrase', 'wp-generate-passphrase' ), self::CAPABILITY, self::MENU_SLUG, array(
			&$this,
			'settings_page'
		) );
		add_filter( "plugin_action_links_$this->base_file_path", array( &$this, 'settings_link' ) );

	}


	/**
	 * Add Settings Link to plugins page
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function settings_link( $links ) {

		$settings_url  = menu_page_url( self::MENU_SLUG, false );
		$settings_link = "<a href='$settings_url'>" . esc_html__( "Settings", "wp-generate-passphrase" ) . "</a>";
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Menu Callback
	 *
	 * @return void
	 */
	public function settings_page() {

		$this->maybe_process_settings_form();

		// Render the settings template
		include( __DIR__ . "/../admin.php" );

	}


	/**
	 * Process the admin page settings form submission
	 *
	 * @return void
	 */
	private function maybe_process_settings_form() {

		if ( ! ( isset( $_POST['_wpnonce'] ) && check_admin_referer( 'WPGP_admin_nonce' ) ) ) {
			return;
		}

		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		// Process the user's custom list of extra words
		$arrExtraWords = null;
		if ( isset( $_POST['ExtraWords'] ) ) {
			// Catch user input and replace commas & spaces with line breaks
			$strExtraWords = str_replace( " ", "\n", str_replace( ",", "\n", $_POST['ExtraWords'] ) );

			// Explode input into an array that we can work with
			$arrExtraWords = explode( "\n", $strExtraWords );

			// Remove all extra white space from each word
			$arrExtraWords = array_map( 'trim', $arrExtraWords );

			// Filter out any words that fail our tests
			$arrExtraWords = array_filter( $arrExtraWords, array(&$this, 'validate_user_words') );

			// Do final sanitization on all our values
			$arrExtraWords = array_map( 'wp_unslash', $arrExtraWords );
			$arrExtraWords = array_map( 'esc_html', $arrExtraWords );
		}
		update_option( "wp_generate_passphrase_extrawords", $arrExtraWords );

		// Show message on admin page
		add_settings_error( 'WPGP-notices', esc_attr( 'settings_updated' ), esc_html__( 'Settings Saved.', 'wp-generate-passphrase' ), 'updated' );

	}

	/**
	 * Used to filter word lists for arrays
	 * If word does not pass all tests, it is filtered out of the results by returning False
	 *
	 * @param $str
	 *
	 * @return bool
	 */
	public function validate_user_words( $str ) {

		// Fail if word is less than N characters long
		if ( strlen( $str ) < 3 )
			return false;

		// If we fall through to here, the word is okay
		return true;
	}


}
