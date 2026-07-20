<?php
/** Gmail SMTP transport for transactional messages from the site. */

defined( 'ABSPATH' ) || exit;

function olga_configure_smtp( $phpmailer ) {
	$password = get_option( 'olga_smtp_app_password', '' );

	if ( ! is_string( $password ) || '' === $password ) {
		return;
	}

	$phpmailer->isSMTP();
	$phpmailer->Host       = 'smtp.gmail.com';
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Port       = 587;
	$phpmailer->SMTPSecure = 'tls';
	$phpmailer->Username   = 'criptex57@gmail.com';
	$phpmailer->Password   = $password;
	$phpmailer->From       = 'criptex57@gmail.com';
	$phpmailer->FromName   = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$phpmailer->CharSet    = 'UTF-8';
}
add_action( 'phpmailer_init', 'olga_configure_smtp' );

function olga_smtp_from_address() {
	return 'criptex57@gmail.com';
}
add_filter( 'wp_mail_from', 'olga_smtp_from_address' );

function olga_smtp_from_name() {
	return wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
}
add_filter( 'wp_mail_from_name', 'olga_smtp_from_name' );
