<?php
/** Site header. */
defined( 'ABSPATH' ) || exit;
$favicon_path    = get_template_directory() . '/assets/src/images/favicon.svg';
$favicon_version = file_exists( $favicon_path ) ? filemtime( $favicon_path ) : wp_get_theme()->get( 'Version' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#2e2a27">
	<link rel="icon" href="<?php echo esc_url( add_query_arg( 'ver', $favicon_version, get_template_directory_uri() . '/assets/src/images/favicon.svg' ) ); ?>" type="image/svg+xml">
	<link rel="icon" href="<?php echo esc_url( add_query_arg( 'ver', $favicon_version, get_template_directory_uri() . '/assets/src/images/favicon-32.png' ) ); ?>" sizes="32x32" type="image/png">
	<link rel="apple-touch-icon" href="<?php echo esc_url( add_query_arg( 'ver', $favicon_version, get_template_directory_uri() . '/assets/src/images/apple-touch-icon.png' ) ); ?>" sizes="180x180">
	<meta name="description" content="<?php echo esc_attr( olga_localized_option( 'seo_description', '', get_bloginfo( 'description' ) ) ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( wp_get_document_title() ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( olga_localized_option( 'seo_description', '', get_bloginfo( 'description' ) ) ); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( olga_language_url( olga_current_language() ) ); ?>">
	<script>(function(d){if(!window.matchMedia('(prefers-reduced-motion: reduce)').matches){d.classList.add('motion-enabled');window.setTimeout(function(){if(!d.classList.contains('motion-app-ready'))d.classList.remove('motion-enabled');},4000);}})(document.documentElement);</script>
	<?php wp_head(); ?>
	<script type="application/ld+json"><?php echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'ProfessionalService', 'name' => olga_localized_option( 'name', 'name_default', 'Ольга Максименко' ), 'url' => olga_url( home_url( '/' ) ), 'email' => olga_option( 'email' ), 'telephone' => olga_option( 'phone' ), 'sameAs' => array_filter( array( olga_option( 'instagram' ), olga_option( 'telegram' ) ) ) ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php echo esc_html( olga_t( 'skip_content' ) ); ?></a>
<div class="page-noise" aria-hidden="true"></div>
<div class="ambient-world" aria-hidden="true">
	<i class="ambient-world__orb ambient-world__orb--one"></i>
	<i class="ambient-world__orb ambient-world__orb--two"></i>
	<i class="ambient-world__orb ambient-world__orb--three"></i>
	<svg class="ambient-world__lines" viewBox="0 0 1440 900" preserveAspectRatio="none">
		<path d="M-80 160 C340 20 620 420 1520 120"/>
		<path d="M-50 760 C430 490 840 990 1510 610"/>
		<circle cx="1120" cy="330" r="230"/>
	</svg>
</div>
<div class="page-transition" aria-hidden="true"><span>OM</span></div>
<header class="site-header" data-header>
	<a class="brand" href="<?php echo esc_url( olga_url( home_url( '/' ) ) ); ?>" aria-label="<?php echo esc_attr( olga_t( 'home_aria' ) ); ?>">
		<span class="brand__name"><?php echo esc_html( olga_localized_option( 'name', 'name_default', 'Ольга Максименко' ) ); ?></span>
		<span class="brand__role"><?php echo esc_html( olga_localized_option( 'role', 'role_default', 'Дизайнер интерьеров' ) ); ?></span>
	</a>
	<nav class="desktop-nav" aria-label="<?php echo esc_attr( olga_t( 'primary_nav' ) ); ?>">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'olga_nav_fallback' ) ); ?>
	</nav>
	<div class="header-actions">
		<?php olga_language_switcher(); ?>
		<button class="button button--small desktop-cta" type="button" data-open-contact><?php echo esc_html( olga_t( 'discuss_project' ) ); ?></button>
		<button class="menu-toggle" type="button" aria-label="<?php echo esc_attr( olga_t( 'open_menu' ) ); ?>" aria-expanded="false" data-menu-toggle><span></span><span></span></button>
	</div>
</header>
<div class="mobile-menu" aria-hidden="true" data-mobile-menu>
	<nav aria-label="<?php echo esc_attr( olga_t( 'mobile_nav' ) ); ?>"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'olga_nav_fallback' ) ); ?></nav>
	<button class="button" type="button" data-open-contact><?php echo esc_html( olga_t( 'discuss_project' ) ); ?></button>
</div>
<main id="main">
