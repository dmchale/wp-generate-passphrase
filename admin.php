<div class="wrap">
    <h1><?php echo esc_html__( "WP Generate Passphrase", "wp-generate-passphrase" ); ?></h1>
	<?php settings_errors( 'WPGP-notices' ); ?>
    <p><?php echo esc_html__( "By default, this plugin uses a stock dictionary of over 7,700 words to generate a random 4-word passphrase.", "wp-generate-passphrase" ); ?></p>

    <form method="post" action="" id="WPGP_form">
		<?php wp_nonce_field( 'WPGP_admin_nonce' ); ?>

        <h2><?php echo esc_html__("Extra Word List", "wp-generate-passphrase" ); ?></h2>
        <p><?php echo esc_html__("You may add your own words to the dictionary list used. Separate words with line breaks. Words must be at least 3 letters long.", "wp-generate-passphrase" ); ?></p>
        <textarea name="ExtraWords" style="width:60%;height:200px;"><?php echo implode( "\n", get_option( "wp_generate_passphrase_extrawords" ) ); ?></textarea>

		<?php submit_button(); ?>
    </form>
</div>
