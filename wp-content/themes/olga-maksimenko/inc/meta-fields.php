<?php
/** Native editable meta fields. */

defined( 'ABSPATH' ) || exit;

function olga_meta_schema() {
	return array(
		'project'     => array(
			'_olga_title_uk'    => array( 'Название — українська', 'text' ),
			'_olga_title_en'    => array( 'Название — English', 'text' ),
			'_olga_excerpt_uk'  => array( 'Краткое описание — українська', 'textarea' ),
			'_olga_excerpt_en'  => array( 'Краткое описание — English', 'textarea' ),
			'_olga_content_uk'  => array( 'Описание проекта — українська', 'textarea' ),
			'_olga_content_en'  => array( 'Описание проекта — English', 'textarea' ),
			'_olga_city'     => array( 'Город', 'text' ),
			'_olga_city_uk'  => array( 'Город — українська', 'text' ),
			'_olga_city_en'  => array( 'Город — English', 'text' ),
			'_olga_area'     => array( 'Площадь', 'text' ),
			'_olga_year'     => array( 'Год', 'number' ),
			'_olga_task'     => array( 'Задача проекта', 'textarea' ),
			'_olga_task_uk'  => array( 'Задача проекта — українська', 'textarea' ),
			'_olga_task_en'  => array( 'Задача проекта — English', 'textarea' ),
			'_olga_solution' => array( 'Ключевые решения', 'textarea' ),
			'_olga_solution_uk' => array( 'Ключевые решения — українська', 'textarea' ),
			'_olga_solution_en' => array( 'Ключевые решения — English', 'textarea' ),
			'_olga_gallery'  => array( 'ID изображений галереи через запятую', 'text' ),
		),
		'service'     => array(
			'_olga_title_uk'    => array( 'Название — українська', 'text' ),
			'_olga_title_en'    => array( 'Название — English', 'text' ),
			'_olga_excerpt_uk'  => array( 'Краткое описание — українська', 'textarea' ),
			'_olga_excerpt_en'  => array( 'Краткое описание — English', 'textarea' ),
			'_olga_content_uk'  => array( 'Описание услуги — українська', 'textarea' ),
			'_olga_content_en'  => array( 'Описание услуги — English', 'textarea' ),
			'_olga_duration' => array( 'Ориентировочный срок', 'text' ),
			'_olga_duration_uk' => array( 'Ориентировочный срок — українська', 'text' ),
			'_olga_duration_en' => array( 'Ориентировочный срок — English', 'text' ),
			'_olga_scope'    => array( 'Состав услуги', 'textarea' ),
			'_olga_scope_uk' => array( 'Состав услуги — українська', 'textarea' ),
			'_olga_scope_en' => array( 'Состав услуги — English', 'textarea' ),
		),
		'page'        => array(
			'_olga_title_uk'   => array( 'Название страницы — українська', 'text' ),
			'_olga_title_en'   => array( 'Название страницы — English', 'text' ),
			'_olga_content_uk' => array( 'Содержимое страницы — українська', 'textarea' ),
			'_olga_content_en' => array( 'Содержимое страницы — English', 'textarea' ),
		),
		'testimonial' => array(
			'_olga_object' => array( 'Тип объекта', 'text' ),
			'_olga_city'   => array( 'Город', 'text' ),
		),
	);
}

function olga_add_meta_boxes() {
	foreach ( olga_meta_schema() as $post_type => $fields ) {
		add_meta_box( 'olga_details', 'Данные для сайта', 'olga_render_meta_box', $post_type, 'normal', 'high', array( 'fields' => $fields ) );
	}
}
add_action( 'add_meta_boxes', 'olga_add_meta_boxes' );

function olga_render_meta_box( $post, $box ) {
	wp_nonce_field( 'olga_save_meta', 'olga_meta_nonce' );
	foreach ( $box['args']['fields'] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label></p>', esc_attr( $key ), esc_html( $field[0] ) );
		if ( 'textarea' === $field[1] ) {
			printf( '<textarea class="widefat" rows="4" id="%1$s" name="%1$s">%2$s</textarea>', esc_attr( $key ), esc_textarea( $value ) );
		} else {
			printf( '<input class="widefat" type="%3$s" id="%1$s" name="%1$s" value="%2$s">', esc_attr( $key ), esc_attr( $value ), esc_attr( $field[1] ) );
		}
	}
}

function olga_save_meta( $post_id ) {
	if ( ! isset( $_POST['olga_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['olga_meta_nonce'] ) ), 'olga_save_meta' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$schema = olga_meta_schema();
	$type   = get_post_type( $post_id );
	if ( empty( $schema[ $type ] ) ) {
		return;
	}
	foreach ( $schema[ $type ] as $key => $field ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = 'textarea' === $field[1] ? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'olga_save_meta' );

function olga_project_category_language_fields( $term = null ) {
	$term_id = $term instanceof WP_Term ? $term->term_id : 0;
	$values  = array(
		'uk' => $term_id ? get_term_meta( $term_id, '_olga_name_uk', true ) : '',
		'en' => $term_id ? get_term_meta( $term_id, '_olga_name_en', true ) : '',
	);
	foreach ( array( 'uk' => 'Название — українська', 'en' => 'Название — English' ) as $language => $label ) {
		if ( $term_id ) {
			echo '<tr class="form-field"><th scope="row"><label for="olga-name-' . esc_attr( $language ) . '">' . esc_html( $label ) . '</label></th><td><input id="olga-name-' . esc_attr( $language ) . '" name="olga_name_' . esc_attr( $language ) . '" value="' . esc_attr( $values[ $language ] ) . '"></td></tr>';
		} else {
			echo '<div class="form-field"><label for="olga-name-' . esc_attr( $language ) . '">' . esc_html( $label ) . '</label><input id="olga-name-' . esc_attr( $language ) . '" name="olga_name_' . esc_attr( $language ) . '" value=""></div>';
		}
	}
}
add_action( 'project_category_add_form_fields', 'olga_project_category_language_fields' );
add_action( 'project_category_edit_form_fields', 'olga_project_category_language_fields' );

function olga_save_project_category_language_fields( $term_id ) {
	foreach ( array( 'uk', 'en' ) as $language ) {
		$key = 'olga_name_' . $language;
		if ( isset( $_POST[ $key ] ) ) {
			update_term_meta( $term_id, '_olga_name_' . $language, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'created_project_category', 'olga_save_project_category_language_fields' );
add_action( 'edited_project_category', 'olga_save_project_category_language_fields' );
