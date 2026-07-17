<?php
/** Small security hardening. */

defined( 'ABSPATH' ) || exit;

remove_action( 'wp_head', 'wp_generator' );

function olga_svg_uploads( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'olga_svg_uploads' );

function olga_svg_filetype( $data, $file, $filename, $mimes ) {
	if ( current_user_can( 'manage_options' ) && 'svg' === strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'olga_svg_filetype', 10, 4 );

