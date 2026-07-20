<?php
/** Frontend assets. */

defined( 'ABSPATH' ) || exit;

function olga_enqueue_assets() {
	$theme = wp_get_theme();
	$css   = get_template_directory() . '/assets/dist/app.css';
	$js    = get_template_directory() . '/assets/dist/app.js';
	wp_enqueue_style( 'olga-style', get_template_directory_uri() . '/assets/dist/app.css', array(), file_exists( $css ) ? filemtime( $css ) : $theme->get( 'Version' ) );
	$mobile_css = get_template_directory() . '/assets/src/css/mobile.css';
	wp_enqueue_style( 'olga-mobile', get_template_directory_uri() . '/assets/src/css/mobile.css', array( 'olga-style' ), file_exists( $mobile_css ) ? filemtime( $mobile_css ) : $theme->get( 'Version' ), '(max-width: 767px)' );
	$palette_css = get_template_directory() . '/assets/src/css/palette.css';
	wp_enqueue_style( 'olga-palette', get_template_directory_uri() . '/assets/src/css/palette.css', array( 'olga-style', 'olga-mobile' ), file_exists( $palette_css ) ? filemtime( $palette_css ) : $theme->get( 'Version' ) );
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

/** Admin-only controls for project galleries and the home-page project selection. */
function olga_enqueue_admin_assets( $hook ) {
	$screen          = get_current_screen();
	$is_project_edit = $screen && 'post' === $screen->base && 'project' === $screen->post_type;
	$is_home_picker  = 'project_page_olga-home-projects' === $hook;

	if ( ! $is_project_edit && ! $is_home_picker ) {
		return;
	}

	if ( $is_project_edit ) {
		wp_enqueue_media();
	}

	$css = get_template_directory() . '/assets/src/css/admin.css';
	$js  = get_template_directory() . '/assets/src/js/admin.js';
	wp_enqueue_style( 'olga-admin', get_template_directory_uri() . '/assets/src/css/admin.css', array(), file_exists( $css ) ? filemtime( $css ) : null );
	wp_enqueue_script( 'olga-admin', get_template_directory_uri() . '/assets/src/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), file_exists( $js ) ? filemtime( $js ) : null, true );
}
add_action( 'admin_enqueue_scripts', 'olga_enqueue_admin_assets' );
