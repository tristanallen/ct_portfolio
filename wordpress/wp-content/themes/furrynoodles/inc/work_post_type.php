<?php
/**
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 *
 */

register_post_type( 'work', array(
  'labels'             => array(
    'name'               => 'Works',
    'singular_name'      => 'Work',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Work',
    'edit_item'          => 'Edit Work',
    'new_item'           => 'New Work',
    'all_items'          => 'All Works',
    'view_item'          => 'View Work',
    'search_items'       => 'Search Works',
    'not_found'          => 'No works found',
    'not_found_in_trash' => 'No work found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Works'
  ),
  'public'             => true,
  'publicly_queryable' => true,
  'show_ui'            => true,
  'show_in_menu'       => true,
  'query_var'          => true,
  'capability_type'    => 'post',
  'has_archive'        => true,
  'hierarchical'       => false,
  'menu_position'      => null,
  'supports'           => array( 'title', 'author', 'thumbnail', 'excerpt' )
  ) );

add_action( 'add_meta_boxes', 'details_add_meta_box' );
function details_add_meta_box()
{
  add_meta_box('details', 'Details', 'details_meta_box', 'work');
}

function details_meta_box($post)
{

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'work_details_nonce', 'work_details_nonce' );

  $value = get_post_meta( $post->ID, '_work_details_client', true );
  echo '<div><label for="work_client">Client: </label>';
  echo '<input type="text" id="work_client" name="work_details_client" value="' . esc_attr( $value ) . '" size="25" /></div>';

  $value = get_post_meta( $post->ID, '_work_details_whatwedid', true );
  echo '<div><label for="work_whatwedid">What We Did: </label>';
  echo '<input type="text" id="work_whatwedid" name="work_details_whatwedid" value="' . esc_attr( $value ) . '" size="25" /></div>';

}

function work_details_save_postdata( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['work_details_nonce'] ) )
    return $post_id;

  $nonce = $_POST['work_details_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'work_details_nonce' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'work' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.

  $meta_fields = array('work_details_client', 'work_details_whatwedid');

  foreach ($meta_fields as $meta)
  {
    $meta_field = sanitize_text_field( $_POST[$meta] );
    update_post_meta( $post_id, '_'.$meta, $meta_field );
  }
  
}
add_action( 'save_post', 'work_details_save_postdata' );