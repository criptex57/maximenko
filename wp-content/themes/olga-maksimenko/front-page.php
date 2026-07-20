<?php
/** Front page. */
get_header();
$default_project_args = array( 'post_type' => 'project', 'posts_per_page' => 6, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) );
$visual_projects      = new WP_Query( $default_project_args );
$selected_project_ids = olga_home_project_ids();
$projects             = $selected_project_ids
	? new WP_Query( array( 'post_type' => 'project', 'post__in' => $selected_project_ids, 'posts_per_page' => 6, 'orderby' => 'post__in' ) )
	: $visual_projects;
$services = new WP_Query( array( 'post_type' => 'service', 'posts_per_page' => 7, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
$project_ids = wp_list_pluck( $visual_projects->posts, 'ID' );
$hero_image  = ! empty( $project_ids ) ? get_the_post_thumbnail_url( $project_ids[0], 'olga-hero' ) : '';
$after_image = ! empty( $project_ids[1] ) ? get_the_post_thumbnail_url( $project_ids[1], 'olga-hero' ) : $hero_image;
$manifesto_lines = preg_split( '/<br\s*\/?\s*>/i', olga_t( 'manifesto_title' ) );
$experience_raw  = (string) olga_option( 'experience', '8+' );
$projects_raw    = (string) olga_option( 'projects_count', '60+' );
$stat_value      = static function ( $value ) {
	return preg_match( '/\d+/', $value, $match ) ? (int) $match[0] : 0;
};
$stat_suffix     = static function ( $value ) {
	return trim( preg_replace( '/\d+/', '', $value, 1 ) );
};
?>
<section class="hero-refined" aria-labelledby="hero-title" data-hero>
	<div class="hero-refined__light" data-depth="0.05" aria-hidden="true"></div>
	<div class="hero-refined__portrait" data-depth="0.12">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/src/images/olga-working-editorial-v3.png' ); ?>" alt="<?php echo esc_attr( olga_t( 'hero_alt' ) ); ?>" width="1536" height="1024" fetchpriority="high">
		<div class="hero-refined__portrait-shade" aria-hidden="true"></div>
	</div>
	<div class="hero-refined__copy">
		<h1 id="hero-title" class="hero-refined__title"><span><?php echo esc_html( olga_t( 'hero_title_1' ) ); ?></span><span><?php echo esc_html( olga_t( 'hero_title_2' ) ); ?></span></h1>
		<p><?php echo esc_html( olga_t( 'hero_text' ) ); ?></p>
		<a class="hero-refined__cta magnetic" href="<?php echo esc_url( olga_section_url( 'projects' ) ); ?>" data-section-link="projects"><span class="ui-arrow-right" aria-hidden="true"></span> <?php echo esc_html( olga_t( 'view_projects' ) ); ?></a>
	</div>
</section>

<section class="manifesto" id="about" data-manifesto>
	<div class="manifesto__sticky">
		<p class="eyebrow">01 · <?php echo esc_html( olga_t( 'manifesto' ) ); ?></p>
		<h2 data-manifesto-title><?php foreach ( $manifesto_lines as $line ) : ?><span class="manifesto-line"><span class="manifesto-line__inner"><?php echo wp_kses_post( $line ); ?></span></span><?php endforeach; ?></h2>
		<p class="manifesto__note"><?php echo esc_html( olga_localized_option( 'about_text', 'about_text_default' ) ); ?></p>
		<a class="text-link magnetic" href="<?php echo esc_url( olga_url( home_url( '/about/' ) ) ); ?>"><?php echo esc_html( olga_t( 'about_approach' ) ); ?> <span class="ui-arrow" aria-hidden="true"></span></a>
	</div>
	<div class="manifesto__collage">
		<div class="manifesto__arch" aria-hidden="true"></div>
		<figure class="collage-photo collage-photo--a" data-manifesto-photo><div class="collage-photo__media"><?php if ( ! empty( $project_ids[2] ) ) { echo get_the_post_thumbnail( $project_ids[2], 'olga-project', array( 'loading' => 'lazy', 'alt' => olga_t( 'interior_detail_alt' ) ) ); } ?></div><figcaption>Light / form</figcaption></figure>
		<figure class="collage-photo collage-photo--b" data-manifesto-photo><div class="collage-photo__media"><?php if ( ! empty( $project_ids[3] ) ) { echo get_the_post_thumbnail( $project_ids[3], 'olga-project', array( 'loading' => 'lazy', 'alt' => olga_t( 'interior_visual_alt' ) ) ); } ?></div><figcaption>Material / silence</figcaption></figure>
		<figure class="collage-photo collage-photo--c" data-manifesto-photo><div class="collage-photo__media"><?php if ( ! empty( $project_ids[4] ) ) { echo get_the_post_thumbnail( $project_ids[4], 'olga-project', array( 'loading' => 'lazy', 'alt' => olga_t( 'interior_modern_alt' ) ) ); } ?></div><figcaption>Human / space</figcaption></figure>
		<div class="manifesto__circle" aria-hidden="true"><span>Function<br>×<br>Emotion</span></div>
	</div>
	<div class="manifesto__stats">
		<div class="manifesto-stat">
			<i class="manifesto-stat__icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M8 40V24l10-9v25M18 40V8l13 8v24M31 40V23l9 5v12M4 40h40"/></svg></i>
			<strong><span data-manifesto-counter="<?php echo esc_attr( $stat_value( $experience_raw ) ); ?>"><?php echo esc_html( $stat_value( $experience_raw ) ); ?></span><sup><?php echo esc_html( $stat_suffix( $experience_raw ) ); ?></sup></strong>
			<span><?php echo esc_html( olga_t( 'stat_experience' ) ); ?></span>
		</div>
		<div class="manifesto-stat">
			<i class="manifesto-stat__icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="m12 30 5-19 20 5-5 20-20-6Zm-3 4 19 7 8-21M15 27l16 4"/></svg></i>
			<strong><span data-manifesto-counter="<?php echo esc_attr( $stat_value( $projects_raw ) ); ?>"><?php echo esc_html( $stat_value( $projects_raw ) ); ?></span><sup><?php echo esc_html( $stat_suffix( $projects_raw ) ); ?></sup></strong>
			<span><?php echo esc_html( olga_t( 'stat_projects' ) ); ?></span>
		</div>
		<div class="manifesto-stat">
			<i class="manifesto-stat__icon manifesto-stat__icon--cycle" aria-hidden="true"><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="17"/><path d="M34 13h7v7M40 14A20 20 0 0 0 8 19M14 35H7v-7M8 34a20 20 0 0 0 32-5"/></svg></i>
			<strong><span data-manifesto-counter="360">360</span><sup>°</sup></strong>
			<span><?php echo esc_html( olga_t( 'stat_full_cycle' ) ); ?></span>
		</div>
	</div>
</section>

<section class="projects-story" id="projects" data-horizontal>
	<div class="projects-story__pin">
		<header class="projects-story__intro">
			<p class="eyebrow">02 · <?php echo esc_html( olga_t( 'selected_works' ) ); ?></p>
			<h2 data-split><?php echo wp_kses_post( olga_t( 'projects_title' ) ); ?></h2>
			<p><?php echo esc_html( olga_t( 'projects_intro' ) ); ?></p>
			<div class="projects-story__progress"><i></i><span>01 / <?php echo esc_html( str_pad( (string) max( 1, $projects->post_count ), 2, '0', STR_PAD_LEFT ) ); ?></span></div>
		</header>
		<div class="projects-track">
		<?php if ( $projects->have_posts() ) : $i = 0; while ( $projects->have_posts() ) : $projects->the_post(); ++$i; $terms = get_the_terms( get_the_ID(), 'project_category' ); ?>
			<article class="story-card story-card--<?php echo esc_attr( $i ); ?>">
				<a href="<?php echo esc_url( olga_url( get_permalink() ) ); ?>" data-transition-link>
					<div class="story-card__index">0<?php echo esc_html( $i ); ?></div>
					<div class="story-card__image"><?php the_post_thumbnail( 'olga-project', array( 'loading' => 'lazy', 'alt' => olga_t( 'project_image_alt', olga_post_value( get_the_ID(), 'title' ) ) ) ); ?><span class="story-card__glow" aria-hidden="true"></span></div>
					<div class="story-card__info"><p><?php echo esc_html( $terms ? olga_term_name( $terms[0] ) : olga_t( 'interior' ) ); ?> · <?php echo esc_html( olga_post_value( get_the_ID(), 'city' ) ); ?></p><h3><?php echo esc_html( olga_post_value( get_the_ID(), 'title' ) ); ?></h3><span><?php echo esc_html( olga_post_value( get_the_ID(), 'area' ) ); ?> / <?php echo esc_html( olga_post_value( get_the_ID(), 'year' ) ); ?></span></div>
				</a>
			</article>
		<?php endwhile; wp_reset_postdata(); endif; ?>
			<div class="projects-story__outro">
				<span class="projects-story__outro-title"><?php echo esc_html( olga_t( 'explore' ) ); ?></span>
				<a class="projects-archive-link magnetic" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">
					<span><?php echo esc_html( olga_t( 'all_projects' ) ); ?></span><i class="projects-archive-link__arrow" aria-hidden="true"><span class="ui-arrow"></span></i>
				</a>
			</div>
		</div>
	</div>
</section>

<section class="services-experience" id="services" data-services>
	<div class="services-experience__title">
		<p class="eyebrow">03 · <?php echo esc_html( olga_t( 'expertise' ) ); ?></p>
		<h2 data-split><?php echo wp_kses_post( olga_t( 'services_title' ) ); ?></h2>
		<p><?php echo esc_html( olga_t( 'services_intro' ) ); ?></p>
	</div>
	<div class="services-list">
		<?php if ( $services->have_posts() ) : $i = 0; while ( $services->have_posts() ) : $services->the_post(); ++$i; ?>
			<article class="service-row" data-service-row><span class="service-row__number"><?php echo esc_html( str_pad( (string) $i, 2, '0', STR_PAD_LEFT ) ); ?></span><div><h3><?php echo esc_html( olga_post_value( get_the_ID(), 'title' ) ); ?></h3><p><?php echo esc_html( olga_post_value( get_the_ID(), 'excerpt' ) ); ?></p></div><span class="service-row__duration"><?php echo esc_html( olga_post_value( get_the_ID(), 'duration' ) ); ?></span><a class="magnetic" href="<?php echo esc_url( olga_url( get_permalink() ) ); ?>" aria-label="<?php echo esc_attr( olga_t( 'more_about', olga_post_value( get_the_ID(), 'title' ) ) ); ?>"><span class="ui-arrow" aria-hidden="true"></span></a><i aria-hidden="true"></i></article>
		<?php endwhile; wp_reset_postdata(); endif; ?>
	</div>
</section>

<section class="portal" data-portal>
	<div class="portal__frame">
		<?php if ( $after_image ) : ?><img src="<?php echo esc_url( $after_image ); ?>" alt="<?php echo esc_attr( olga_t( 'portal_alt' ) ); ?>" loading="lazy"><?php endif; ?>
		<div class="portal__veil"></div>
		<span class="portal__word portal__word--one">FEEL</span>
		<span class="portal__word portal__word--two">SPACE</span>
		<svg viewBox="0 0 1200 700" preserveAspectRatio="none" aria-hidden="true"><path d="M50 350 C300 50 900 650 1150 350"/><circle cx="600" cy="350" r="260"/></svg>
	</div>
</section>

<section class="process section" id="process" data-process>
	<div class="section-heading"><p class="eyebrow">04 · <?php echo esc_html( olga_t( 'process' ) ); ?></p><h2 data-split><?php echo wp_kses_post( olga_t( 'process_title' ) ); ?></h2><p class="process__intro"><?php echo esc_html( olga_t( 'process_intro' ) ); ?></p></div>
	<ol class="process-list">
		<?php $steps = array_filter( array_map( 'trim', explode( "\n", olga_localized_option( 'process_steps', 'process_steps_default' ) ) ) ); foreach ( $steps as $i => $step ) : ?>
			<li><span><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $step ); ?></h3><p><?php echo esc_html( ( $i + 1 ) . ' / 8' ); ?></p><i aria-hidden="true"></i></li>
		<?php endforeach; ?>
	</ol>
</section>

<?php if ( $hero_image && $after_image ) : ?>
<section class="comparison section" data-comparison>
	<div class="comparison__ghost" aria-hidden="true">TRANSFORM</div>
	<div class="section-heading section-heading--split"><div><p class="eyebrow">05 · <?php echo esc_html( olga_t( 'transformation' ) ); ?></p><h2 data-split><?php echo esc_html( olga_t( 'before_after' ) ); ?></h2></div><p><?php echo esc_html( olga_t( 'compare_instruction' ) ); ?></p></div>
	<div class="before-after" data-before-after style="--position:50%">
		<img class="before-after__after" src="<?php echo esc_url( $after_image ); ?>" alt="<?php echo esc_attr( olga_t( 'after_alt' ) ); ?>">
		<div class="before-after__before"><img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( olga_t( 'before_alt' ) ); ?>"></div>
		<input type="range" min="0" max="100" value="50" aria-label="<?php echo esc_attr( olga_t( 'compare_aria' ) ); ?>">
		<div class="before-after__handle" aria-hidden="true"><span>↔</span></div>
		<span class="before-after__label before-after__label--before"><?php echo esc_html( olga_t( 'before' ) ); ?></span><span class="before-after__label before-after__label--after"><?php echo esc_html( olga_t( 'after' ) ); ?></span>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
