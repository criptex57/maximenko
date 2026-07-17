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

