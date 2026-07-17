<?php
/** Contact form endpoint. */
defined( 'ABSPATH' ) || exit;

function olga_register_contact_route() {
	register_rest_route( 'olga/v1', '/contact', array( 'methods' => 'POST', 'callback' => 'olga_handle_contact', 'permission_callback' => '__return_true' ) );
}
add_action( 'rest_api_init', 'olga_register_contact_route' );

function olga_handle_contact( WP_REST_Request $request ) {
	$data = $request->get_json_params();
	$language = isset( $data['language'] ) && array_key_exists( sanitize_key( $data['language'] ), olga_languages() ) ? sanitize_key( $data['language'] ) : 'ru';
	if ( ! empty( $data['website'] ) ) {
		return new WP_Error( 'spam', olga_t_for( 'form_rejected', $language ), array( 'status' => 400 ) );
	}
	$name    = sanitize_text_field( $data['name'] ?? '' );
	$contact = sanitize_text_field( $data['contact'] ?? '' );
	$email   = sanitize_email( $data['email'] ?? '' );
	$agree   = ! empty( $data['agree'] );
	if ( mb_strlen( $name ) < 2 || mb_strlen( $contact ) < 5 || ! $agree ) {
		return new WP_Error( 'validation', olga_t_for( 'form_validation', $language ), array( 'status' => 422 ) );
	}
	$ip_key = 'olga_form_' . md5( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
	if ( get_transient( $ip_key ) ) {
		return new WP_Error( 'rate_limit', olga_t_for( 'form_rate', $language ), array( 'status' => 429 ) );
	}
	set_transient( $ip_key, 1, MINUTE_IN_SECONDS );
	$labels = array( 'name' => 'Имя', 'contact' => 'Телефон / мессенджер', 'email' => 'Email', 'object' => 'Тип объекта', 'area' => 'Площадь', 'city' => 'Город', 'budget' => 'Бюджет', 'message' => 'Сообщение' );
	$lines  = array();
	foreach ( $labels as $key => $label ) {
		$lines[] = $label . ': ' . sanitize_textarea_field( $data[ $key ] ?? '' );
	}
	$headers = $email ? array( 'Reply-To: ' . $name . ' <' . $email . '>' ) : array();
	$sent    = wp_mail( olga_option( 'form_email', get_option( 'admin_email' ) ), 'Новая заявка с сайта', implode( "\n", $lines ), $headers );
	if ( ! $sent && 'local' !== wp_get_environment_type() ) {
		return new WP_Error( 'mail', olga_t_for( 'form_mail_error', $language ), array( 'status' => 500 ) );
	}
	return rest_ensure_response( array( 'success' => true, 'message' => olga_t_for( 'form_success', $language ) ) );
}
