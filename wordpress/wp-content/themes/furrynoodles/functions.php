<?php
/**
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 *
 */

add_theme_support( 'post-thumbnails' );
add_image_size( 'tc_thumb', '480', '360', true );

require_once dirname(__FILE__) . '/inc/work_post_type.php';
add_action( 'pre_get_posts', 'use_work_posts' );
function use_work_posts( $query )
{
  if( $query->is_home() && $query->is_main_query() ){
    $query->set( 'post_type', 'work' );
  }
}