<?php get_header(); ?>
<header class="page-hero section"><?php olga_breadcrumbs(); ?><p class="eyebrow"><?php echo esc_html( olga_t( 'portfolio' ) ); ?></p><h1><?php echo esc_html( olga_t( 'archive_title' ) ); ?></h1><p><?php echo esc_html( olga_t( 'archive_intro' ) ); ?></p></header>
<section class="archive-grid section">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<article class="project-card"><a href="<?php echo esc_url( olga_url( get_permalink() ) ); ?>"><div class="project-card__image"><?php the_post_thumbnail( 'olga-project', array( 'alt' => olga_t( 'project_image_alt', olga_post_value( get_the_ID(), 'title' ) ) ) ); ?><span class="project-card__open"><?php echo esc_html( olga_t( 'open' ) ); ?> <span class="ui-arrow" aria-hidden="true"></span></span></div><div class="project-card__info"><h2><?php echo esc_html( olga_post_value( get_the_ID(), 'title' ) ); ?></h2><span><?php echo esc_html( implode( ' · ', olga_project_meta() ) ); ?></span></div></a></article>
	<?php endwhile; the_posts_pagination(); else : ?><p><?php echo esc_html( olga_t( 'projects_soon' ) ); ?></p><?php endif; ?>
</section>
<?php get_footer(); ?>
