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

		add_action( 'admin_menu', array( &$this, 'define_admin_link' ) );

		add_filter( 'random_password', array( &$this, 'wp_generate_passphrase' ), 20 );

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

		// TODO: DO STUFF WHEN ADMIN PAGE IS SUBMITTED

	}


	/**
	 * This is called when the Option is detected to be empty
	 * Read the text file included with this plugin and parse it to create our default dictionary
	 * File originally taken from https://www.eff.org/deeplinks/2016/07/new-wordlists-random-passphrases
	 */
	private function create_option_from_text_file() {
		$filePath = plugin_dir_path( __DIR__ ) . "includes/eff_large_wordlist.txt";
		if ( is_file( $filePath ) ) {
			$words = file( $filePath, FILE_IGNORE_NEW_LINES );
			update_option( 'wp_generate_passphrase_dictionary', $words );
		}
	}


	/**
	 * Filter handler for the usual results of `wp_generate_password()`
	 * Throw out whatever it used to be and return a new value
	 *
	 * @param $password
	 *
	 * @return string
	 */
	public function wp_generate_passphrase( $password ) {
		$password = '';

		$words = $this->get_words();
		if ( ! $words ) {
			return 'ERROR:NO_WORDS_FOUND';
		}

		for ( $x = 1; $x <= 4; $x ++ ) {
			$password .= $this->get_random_word( $words );
		}

		return $password;
	}


	/**
	 * Build our list of words based on the default dictionary plus any custom rules defined by the user
	 *
	 * @return mixed
	 */
	private function get_words() {
		$words = get_option( 'wp_generate_passphrase_dictionary' );

		// Self-healing in case the option is empty when we try to access it
		if ( ! $words ) {
			$this->create_option_from_text_file();
			$words = get_option( 'wp_generate_passphrase_dictionary' );
		}

		// TODO: Add functionality that relies on user input for adding new words, removing words, etc

		return $words;
	}


	/**
	 * Given an array of words, return a single random one
	 *
	 * @param $words
	 *
	 * @return mixed
	 */
	private function get_random_word( $words ) {
		$maxIndex = count( $words ) - 1;
		if ( $maxIndex < 0 ) {
			return 'ERROR';
		}

		$randomIndex = wp_rand( 0, $maxIndex );

		return ucfirst( $words[ $randomIndex ] );
	}
}
