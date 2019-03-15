<div class="wrap">
    <h1><?php echo esc_html__( "WP Generate Passphrase", "wp-generate-passphrase" ); ?></h1>
	<?php settings_errors( 'WPGP-notices' ); ?>
    <p><?php echo esc_html__( "By default, this plugin uses a stock dictionary of over 7,000 words to generate a random 4-word passphrase. You may use this page to modify this behavior.", "wp-generate-passphrase" ); ?></p>

    <form method="post" action="" id="WPGP_form">
		<?php wp_nonce_field( 'WPGP_admin_nonce' ); ?>

        <div id="WPGP_container">Hello there! Admin options are coming soon!</div>

        <?php if ( get_option( 'wp_generate_passphrase_dictionary' ) ) {
            var_dump( count( get_option( 'wp_generate_passphrase_dictionary' ) ) );
        } ?>

		<?php submit_button(); ?>
    </form>
</div>
