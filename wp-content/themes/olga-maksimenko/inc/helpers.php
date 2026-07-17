<?php
/** Template helpers. */

defined( 'ABSPATH' ) || exit;

function olga_option( $key, $fallback = '' ) {
	$options = get_option( 'olga_theme_options', array() );
	return isset( $options[ $key ] ) && '' !== $options[ $key ] ? $options[ $key ] : $fallback;
}

function olga_project_meta( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	return array_filter(
		array(
			olga_t( 'city_label' ) => olga_post_value( $post_id, 'city' ),
			olga_t( 'area_label' ) => olga_post_value( $post_id, 'area' ),
			olga_t( 'year_label' ) => olga_post_value( $post_id, 'year' ),
		)
	);
}

function olga_nav_fallback() {
	$items = array(
		array( 'ru' => 'Обо мне', 'uk' => 'Про мене', 'en' => 'About', 'href' => '#about' ),
		array( 'ru' => 'Проекты', 'uk' => 'Проєкти', 'en' => 'Projects', 'href' => '#projects' ),
		array( 'ru' => 'Услуги', 'uk' => 'Послуги', 'en' => 'Services', 'href' => '#services' ),
		array( 'ru' => 'Этапы', 'uk' => 'Етапи', 'en' => 'Process', 'href' => '#process' ),
		array( 'ru' => 'Контакты', 'uk' => 'Контакти', 'en' => 'Contact', 'href' => '#contacts' ),
	);
	echo '<ul class="menu">';
	foreach ( $items as $item ) {
		$section = ltrim( $item['href'], '#' );
		printf( '<li><a href="%s" data-section-link="%s">%s</a></li>', esc_url( olga_section_url( $section ) ), esc_attr( $section ), esc_html( $item[ olga_current_language() ] ) );
	}
	echo '</ul>';
}

function olga_remove_reviews_menu_item( $items ) {
	return array_values(
		array_filter(
			$items,
			static fn( $item ) => 'reviews' !== wp_parse_url( $item->url, PHP_URL_FRAGMENT )
		)
	);
}
add_filter( 'wp_nav_menu_objects', 'olga_remove_reviews_menu_item' );

function olga_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	if ( is_singular() ) {
		$current = olga_post_value( get_the_ID(), 'title' );
	} elseif ( is_post_type_archive( 'project' ) ) {
		$current = olga_t( 'portfolio' );
	} else {
		$current = wp_get_document_title();
	}
	echo '<nav class="breadcrumbs" aria-label="Breadcrumbs"><a href="' . esc_url( olga_url( home_url( '/' ) ) ) . '">' . esc_html( olga_t( 'home' ) ) . '</a><span aria-hidden="true">/</span><span>' . esc_html( $current ) . '</span></nav>';
}
