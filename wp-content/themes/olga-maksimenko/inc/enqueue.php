<?php
/** Frontend assets. */

defined( 'ABSPATH' ) || exit;

function olga_enqueue_assets() {
	$theme = wp_get_theme();
	$css   = get_template_directory() . '/assets/dist/app.css';
	$js    = get_template_directory() . '/assets/dist/app.js';
	wp_enqueue_style( 'olga-style', get_template_directory_uri() . '/assets/dist/app.css', array(), file_exists( $css ) ? filemtime( $css ) : $theme->get( 'Version' ) );
	wp_enqueue_script( 'olga-app', get_template_directory_uri() . '/assets/dist/app.js', array(), file_exists( $js ) ? filemtime( $js ) : $theme->get( 'Version' ), true );
	wp_localize_script(
		'olga-app',
		'olgaSite',
		array(
			'restUrl' => esc_url_raw( rest_url( 'olga/v1/contact' ) ),
			'nonce'   => wp_create_nonce( 'wp_rest' ),
			'language' => olga_current_language(),
			'initialSection' => olga_current_section(),
			'messages' => array(
				'check'   => olga_t( 'form_check' ),
				'sending' => olga_t( 'form_sending' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'olga_enqueue_assets' );
