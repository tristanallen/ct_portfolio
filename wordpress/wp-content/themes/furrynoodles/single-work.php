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
		<?php the_post(); ?>
			
		<div id="work-wrapper" class="clear">
				<div id="work-main" class="work-content">
          <?php the_content(  ) ?>
        </div>

				<div id="work-sidebar" class="work-content">
					<h2><?php the_title(); ?></h2>
					<div class="work-client">for <?php echo get_post_meta( get_the_ID(), '_work_details_client', true ) ?></div>
          <div class="excerpt">
					<div class="work-excerpt"><?php the_excerpt(  ) ?></div>
          </div>

					<?php else : ?>

						<article id="post-0" class="work-main no-results not-found">
							Hello, we're working on it...
						</article><!-- #post-0 -->

					<?php endif; // end have_posts() check ?>

				</div>

      <div id="work-related-items">
        <h2>More projects</h2>
				<?php
				$currentID = get_the_ID();
				$my_query = new WP_Query( array('post_type'=>'work', 'showposts' => '5', 'post__not_in' => array($currentID)));
        ?>
				<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>
          <div class="work-related-item">
            <?php the_post_thumbnail( 'sidebar_thumb' ) ?>
            <h3><?php the_title() ?> <span class="work-client">for <?php echo get_post_meta( get_the_ID(), '_work_details_client', true ) ?></span></h3>
          </div>
				<?php endwhile; ?>
      </div>

<?php get_footer(); ?>
