<?php
/** Contact form endpoint. */
defined( 'ABSPATH' ) || exit;

function olga_register_contact_route() {
	register_rest_route( 'olga/v1', '/contact', array( 'methods' => 'POST', 'callback' => 'olga_handle_contact', 'permission_callback' => '__return_true' ) );
}
add_action( 'rest_api_init', 'olga_register_contact_route' );

function olga_contact_email_html( $values ) {
	$rows = array(
		'Имя'                   => $values['name'],
		'Телефон / мессенджер' => $values['contact'],
		'Email'                 => $values['email'],
		'Тип объекта'           => $values['object'],
		'Площадь'               => $values['area'],
		'Город'                 => $values['city'],
		'Ориентировочный бюджет' => $values['budget'],
	);

	$details = '';
	foreach ( $rows as $label => $value ) {
		$display_value = '' !== $value ? esc_html( $value ) : '—';
		if ( 'Email' === $label && is_email( $value ) ) {
			$display_value = '<a href="mailto:' . esc_attr( $value ) . '" style="color:#8f684a;text-decoration:none;">' . esc_html( $value ) . '</a>';
		}
		$details .= '<tr>';
		$details .= '<td style="width:42%;padding:14px 16px 14px 0;border-bottom:1px solid #e3dbd1;color:#7b746d;font:500 11px/1.4 Arial,sans-serif;letter-spacing:.08em;text-transform:uppercase;vertical-align:top;">' . esc_html( $label ) . '</td>';
		$details .= '<td style="padding:13px 0 14px;border-bottom:1px solid #e3dbd1;color:#2e2a27;font:400 15px/1.5 Arial,sans-serif;vertical-align:top;">' . $display_value . '</td>';
		$details .= '</tr>';
	}

	$message = '' !== $values['message'] ? nl2br( esc_html( $values['message'] ) ) : 'Сообщение не добавлено.';
	$time    = wp_date( 'd.m.Y · H:i' );
	$site    = home_url( '/' );

	return '<!doctype html><html><body style="margin:0;padding:0;background:#eee7de;color:#2e2a27;">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background:#eee7de;"><tr><td align="center" style="padding:34px 14px;">'
		. '<table role="presentation" width="640" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:640px;background:#f8f5f0;border:1px solid #ded4c8;">'
		. '<tr><td style="height:6px;background:#9d7350;font-size:0;line-height:0;">&nbsp;</td></tr>'
		. '<tr><td style="padding:38px 42px 22px;">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>'
		. '<td style="vertical-align:top;"><div style="margin-bottom:13px;color:#9d7350;font:600 10px/1.4 Arial,sans-serif;letter-spacing:.18em;text-transform:uppercase;">Новая заявка с сайта</div>'
		. '<div style="color:#2e2a27;font:400 34px/1.15 Georgia,serif;">Обсудить проект</div></td>'
		. '<td align="right" style="vertical-align:top;"><div style="display:inline-block;padding:12px 11px;border:1px solid #cdb9a4;color:#8f684a;font:400 18px/1 Georgia,serif;">OM</div></td>'
		. '</tr></table>'
		. '<p style="margin:24px 0 0;color:#6b645d;font:400 14px/1.65 Arial,sans-serif;">Получена новая заявка на разработку интерьера. Контактные данные и параметры проекта собраны ниже.</p>'
		. '</td></tr>'
		. '<tr><td style="padding:0 42px 30px;"><table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">' . $details . '</table></td></tr>'
		. '<tr><td style="padding:0 42px 34px;"><div style="padding:23px 25px;background:#efe8de;border-left:3px solid #b58b62;">'
		. '<div style="margin-bottom:10px;color:#8f684a;font:600 10px/1.4 Arial,sans-serif;letter-spacing:.16em;text-transform:uppercase;">Сообщение</div>'
		. '<div style="color:#3a342f;font:400 15px/1.7 Arial,sans-serif;">' . $message . '</div></div></td></tr>'
		. '<tr><td style="padding:22px 42px;background:#2e2a27;">'
		. '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr>'
		. '<td style="color:#c8beb3;font:400 11px/1.5 Arial,sans-serif;">' . esc_html( $time ) . '</td>'
		. '<td align="right"><a href="' . esc_url( $site ) . '" style="color:#d2b390;font:500 11px/1.5 Arial,sans-serif;letter-spacing:.08em;text-decoration:none;text-transform:uppercase;">mcseamnko-design.pp.ua&nbsp;&nbsp;→</a></td>'
		. '</tr></table></td></tr>'
		. '</table></td></tr></table></body></html>';
}

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
	$values = array(
		'name'    => $name,
		'contact' => $contact,
		'email'   => $email,
		'object'  => sanitize_text_field( $data['object'] ?? '' ),
		'area'    => sanitize_text_field( $data['area'] ?? '' ),
		'city'    => sanitize_text_field( $data['city'] ?? '' ),
		'budget'  => sanitize_text_field( $data['budget'] ?? '' ),
		'message' => sanitize_textarea_field( $data['message'] ?? '' ),
	);
	$inquiry_id = wp_insert_post(
		array(
			'post_type'   => 'inquiry',
			'post_status' => 'publish',
			'post_title'  => $name . ' — ' . wp_date( 'd.m.Y H:i' ),
		),
		true
	);
	if ( ! is_wp_error( $inquiry_id ) ) {
		foreach ( $values as $key => $value ) {
			update_post_meta( $inquiry_id, '_olga_inquiry_' . $key, $value );
		}
		update_post_meta( $inquiry_id, '_olga_inquiry_language', $language );
		update_post_meta( $inquiry_id, '_olga_inquiry_mail_status', 'pending' );
	}
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}
	$sent = wp_mail(
		olga_option( 'form_email', get_option( 'admin_email' ) ),
		'Новая заявка от ' . $name,
		olga_contact_email_html( $values ),
		$headers
	);
	if ( ! is_wp_error( $inquiry_id ) ) {
		update_post_meta( $inquiry_id, '_olga_inquiry_mail_status', $sent ? 'sent' : 'failed' );
	}
	if ( ! $sent && 'local' !== wp_get_environment_type() ) {
		return new WP_Error( 'mail', olga_t_for( 'form_mail_error', $language ), array( 'status' => 500 ) );
	}
	return rest_ensure_response( array( 'success' => true, 'message' => olga_t_for( 'form_success', $language ) ) );
}
