<?php

/**
 * WPGP_Worker class
 *
 * Static method holder for anything not related to the plugin's core itself which doesn't need an instance
 *
 */
class WPGP_Worker {

	/**
	 * Static method to return the processed list of all words, accounting for all user adds/removals/etc
	 *
	 * @return array
	 */
	public static function get_words() {
		// Get values from Options
		$words = get_option( "wp_generate_passphrase_dictionary" );
		$extraWords = get_option( "wp_generate_passphrase_extraWords" );

		// Merge arrays if user has defined their own words
		if ( $extraWords )
			$words = array_merge( $words, $extraWords );

		// Ensure there aren't duplicate words in the word list
		$words = array_unique( $words );

		// Return our processed array of words
		return $words;
	}


	/**
	 * Call this method to return new passphrases on demand
	 *
	 * @return string
	 */
	public static function get_new_passphrase() {
		return self::wp_generate_passphrase( '' );
	}


	/**
	 * DO NOT CALL MANUALLY.
	 *
	 * Used as filter callback to override the usual results of `wp_generate_password()`
	 *
	 * Throw out whatever it used to be and return a new value
	 *
	 * @param $password
	 *
	 * @return string
	 */
	public static function wp_generate_passphrase( $password ) {
		$password = '';

		$words = self::get_words();
		if ( ! $words ) {
			return 'ERROR:NO_WORDS_FOUND';
		}

		for ( $x = 1; $x <= 4; $x ++ ) {
			$password .= self::get_random_word( $words );
		}

		return $password;
	}


	/**
	 * Given an array of words, return a single random one
	 *
	 * @param $words
	 *
	 * @return mixed
	 */
	private static function get_random_word( $words ) {
		$maxIndex = count( $words ) - 1;
		if ( $maxIndex < 0 ) {
			return 'ERROR';
		}

		$randomIndex = wp_rand( 0, $maxIndex );

		return ucfirst( $words[ $randomIndex ] );
	}


}
