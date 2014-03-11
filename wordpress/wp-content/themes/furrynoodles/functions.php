<?php
/**
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 *
 */
require_once dirname(__FILE__) . '/inc/editor-formatting-keyline.php';
require_once dirname(__FILE__) . '/inc/post-multiple-image-attachments.php';

add_filter('show_admin_bar', '__return_false');

add_theme_support( 'post-thumbnails' );
add_image_size( 'home_thumb', '480', '297', true );
add_image_size( 'sidebar_thumb', '150', '150', true );

require_once dirname(__FILE__) . '/inc/work_post_type.php';
add_action( 'pre_get_posts', 'use_work_posts' );
function use_work_posts( $query )
{
  if( $query->is_home() && $query->is_main_query() ){
    $query->set( 'post_type', 'work' );
  }
}