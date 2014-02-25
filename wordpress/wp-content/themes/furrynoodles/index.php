<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 */

get_header(); ?>

	<?php if ( have_posts() ) : ?>

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_title(); ?>
		<?php endwhile; ?>

	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			Hello, we're working on it...
		</article><!-- #post-0 -->

	<?php endif; // end have_posts() check ?>

<?php get_footer(); ?>