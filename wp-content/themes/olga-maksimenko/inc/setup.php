<?php
/** Theme setup. */

defined( 'ABSPATH' ) || exit;

function olga_theme_setup() {
	load_theme_textdomain( 'olga-maksimenko', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	register_nav_menus(
		array(
			'primary' => 'Основное меню',
			'footer'  => 'Меню в подвале',
		)
	);
	add_image_size( 'olga-project', 1200, 900, true );
	add_image_size( 'olga-hero', 1800, 1200, true );
}
add_action( 'after_setup_theme', 'olga_theme_setup' );

function olga_content_width() {
	$GLOBALS['content_width'] = 1440;
}
add_action( 'after_setup_theme', 'olga_content_width', 0 );

