<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Tris woo
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
		<?php $i = 0 ?>
		<?php while ( have_posts() ) : the_post(); $i++ ?>
			
				<div id="work-sidebar" class="work-content"><?php the_title(); ?> <span class="work-client"> for <?php echo get_post_meta( get_the_ID(), '_work_details_client', true ) ?></span></div>
				<div id="work-main" class="work-content"><?php the_content(  ) ?></div>
			  
		<?php endwhile; ?>

	<?php else : ?>

		<article id="post-0" class="work-main no-results not-found">
			Hello, we're working on it...
		</article><!-- #post-0 -->

	<?php endif; // end have_posts() check ?>

<?php get_footer(); ?>