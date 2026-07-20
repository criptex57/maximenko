<?php
/** Custom content types. */

defined( 'ABSPATH' ) || exit;

function olga_register_content_types() {
	register_post_type(
		'project',
		array(
			'labels'       => array( 'name' => 'Проекты', 'singular_name' => 'Проект', 'add_new_item' => 'Добавить проект', 'edit_item' => 'Редактировать проект' ),
			'public'       => true,
			'menu_icon'    => 'dashicons-art',
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'projects' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'service',
		array(
			'labels'       => array( 'name' => 'Услуги', 'singular_name' => 'Услуга', 'add_new_item' => 'Добавить услугу' ),
			'public'       => true,
			'menu_icon'    => 'dashicons-admin-tools',
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'services' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'testimonial',
		array(
			'labels'       => array( 'name' => 'Отзывы', 'singular_name' => 'Отзыв', 'add_new_item' => 'Добавить отзыв' ),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-quote',
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'inquiry',
		array(
			'labels'       => array(
				'name'          => 'Заявки',
				'singular_name' => 'Заявка',
				'edit_item'     => 'Просмотр заявки',
				'view_item'     => 'Просмотр заявки',
				'search_items'  => 'Найти заявку',
				'not_found'     => 'Заявок пока нет',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => false,
			'menu_icon'    => 'dashicons-email-alt',
			'menu_position'=> 25,
			'supports'     => array( 'title' ),
			'map_meta_cap' => true,
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		)
	);

	register_taxonomy(
		'project_category',
		'project',
		array(
			'labels'       => array( 'name' => 'Категории проектов', 'singular_name' => 'Категория' ),
			'public'       => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'project-category' ),
		)
	);
}
add_action( 'init', 'olga_register_content_types' );

function olga_inquiry_meta_box() {
	add_meta_box( 'olga-inquiry-details', 'Данные заявки', 'olga_render_inquiry_details', 'inquiry', 'normal', 'high' );
}
add_action( 'add_meta_boxes_inquiry', 'olga_inquiry_meta_box' );

function olga_render_inquiry_details( $post ) {
	$fields = array(
		'name'        => 'Имя',
		'contact'     => 'Телефон / мессенджер',
		'email'       => 'Email',
		'object'      => 'Тип объекта',
		'area'        => 'Площадь',
		'city'        => 'Город',
		'budget'      => 'Ориентировочный бюджет',
		'message'     => 'Сообщение',
		'language'    => 'Язык сайта',
		'mail_status' => 'Статус письма',
	);

	echo '<table class="widefat striped" style="max-width:900px"><tbody>';
	foreach ( $fields as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, '_olga_inquiry_' . $key, true );
		if ( 'mail_status' === $key ) {
			$value = 'sent' === $value ? 'Отправлено' : 'Ошибка отправки';
		}
		if ( 'email' === $key && is_email( $value ) ) {
			$value_html = '<a href="mailto:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>';
		} elseif ( 'message' === $key ) {
			$value_html = nl2br( esc_html( $value ) );
		} else {
			$value_html = esc_html( '' !== $value ? $value : '—' );
		}
		echo '<tr><th style="width:220px;padding:14px">' . esc_html( $label ) . '</th><td style="padding:14px">' . $value_html . '</td></tr>';
	}
	echo '</tbody></table>';
}

function olga_inquiry_columns( $columns ) {
	return array(
		'cb'              => $columns['cb'],
		'title'           => 'Клиент',
		'inquiry_contact' => 'Контакт',
		'inquiry_object'  => 'Объект',
		'inquiry_city'    => 'Город',
		'inquiry_status'  => 'Письмо',
		'date'            => 'Дата',
	);
}
add_filter( 'manage_inquiry_posts_columns', 'olga_inquiry_columns' );

function olga_inquiry_column_value( $column, $post_id ) {
	$meta_key = array(
		'inquiry_contact' => 'contact',
		'inquiry_object'  => 'object',
		'inquiry_city'    => 'city',
	)[ $column ] ?? '';

	if ( $meta_key ) {
		echo esc_html( get_post_meta( $post_id, '_olga_inquiry_' . $meta_key, true ) ?: '—' );
	}
	if ( 'inquiry_status' === $column ) {
		$status = get_post_meta( $post_id, '_olga_inquiry_mail_status', true );
		echo 'sent' === $status ? '<span style="color:#327a43">Отправлено</span>' : '<span style="color:#a0443d">Ошибка</span>';
	}
}
add_action( 'manage_inquiry_posts_custom_column', 'olga_inquiry_column_value', 10, 2 );
