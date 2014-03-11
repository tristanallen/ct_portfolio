<?php

class Furrynoodles_Multiple_Image_Attachments
{
  private $unique_ref      = 'fn_attachment';
  private $meta_box_id     = 'furrynoodles_multiple_image_attachments';
  private $meta_box_title  = 'Images';
  private $post_type       = 'work';
  private $nonce_action    = 'furrynoodles_multiple_image_attachments';
  private $nonce_name      = 'furrynoodles_multiple_image_attachments_save';
  private $post_id;

  public function __construct()
  {
    //posts_join does not work in admin...
    //add_filter( 'posts_join',   array( $this, 'query_add_join_attachments' ) );
    //add_filter( 'posts_select', array( $this, 'query_add_select_attachments' ) );

    if( is_admin() ){
      add_action( 'admin_head', array( $this, 'on_post_ready' ) );
      add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
      add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
      add_action( 'save_post', array( $this, 'save' ) );
    }
  }

  public function set_post_id( $id )
  {
    $this->post_id = $id;
  }

  public function get_post_id( $id )
  {
    return $this->post_id = $id;
  }

  public function get_meta_key_prefix()
  {
    return '_' . $this->unique_ref . '_';
  }

  public function on_post_ready()
  {
    global $post;
    $this->post_id = $post->ID;
  }

  /*
  private function should_filter_query()
  {
    global $wp_query;
    return is_single() && $wp_query->query_vars['post_type'] == $this->post_type ;
  }

  public function query_add_select_attachments( $select )
  {
    if( !$this->should_filter_query() )
      return $join;

    $select .= str_replace(
      array( 
        '[[UREF]]',
        '[[META_KEY_PREFIX]]' 
      ),
      array( 
        $this->unique_ref,
        $this->get_meta_key_prefix()
      ),
      '
      [[UREF]]_image.url
      '
      );

    return $select;
  }
  */

  /*
  public function query_add_join_attachments( $join )
  {
    if( !$this->should_filter_query() )
      return $join;

    $join .= str_replace(
      array( 
        '[[UREF]]',
        '[[META_KEY_PREFIX]]' 
      ),
      array( 
        $this->unique_ref,
        $this->get_meta_key_prefix()
      ),
      ' 
      LEFT JOIN wp_postmeta as [[UREF]]_link
        ON  [[UREF]]_link.post_id = wp_posts.ID
        AND [[UREF]]_link.meta_key LIKE "[[META_KEY_PREFIX]]%"
      LEFT JOIN wp_posts as [[UREF]]_image
        ON  [[UREF]]_link.post_id = [[UREF]]_image.ID
      ');

    //var_dump( $join );

    return $join;
  }
  */

  public function enqueue_scripts( $hook )
  {
    $hooks = array('post.php', 'edit.php');
    if( !in_array( $hook, $hooks ) ) return;

    wp_enqueue_script( 'furrynoodles_multiple_image_attachments_js',
                       get_template_directory_uri() . '/js/post_multiple_image_attachments.js',
                       array( 'jquery', 'jquery-ui-draggable' )
                       );
    wp_enqueue_style( "furrynoodles_multiple_image_attachments_css",  get_template_directory_uri()."/css/furrynoodles_multiple_image_attachments.css" );

  }

  public function add_meta_box()
  {
    add_meta_box( $this->meta_box_id, 
                  $this->meta_box_title,
                  array( $this, 'get_meta_box_html' ),
                  $this->post_type
                );
  }

  public function get_meta_box_html()
  {
    wp_nonce_field( $this->nonce_action, $this->nonce_name );
    echo $this->get_html_container();
  }

  public function save( $post_id )
  {
    $this->post_id = $post_id;

    // Check if our nonce is set and is valid
    if ( ! isset( $_POST[ $this->nonce_name ] )
         || wp_verify_nonce( $nonce, $this->nonce_action )
       )
      return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return $post_id;

    // Check the user's permissions.
    if ( $this->post_type == $_POST['post_type'] ) {
      if ( ! current_user_can( 'edit_page', $post_id ) )
          return $post_id;
    } else {
      if ( ! current_user_can( 'edit_post', $post_id ) )
          return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    $sql = '
      DELETE
      FROM wp_postmeta
      WHERE meta_key LIKE "%s"
      AND post_id = %d
      ';

    global $wpdb;
    $wpdb->query(
      $wpdb->prepare( $sql, $this->get_meta_key_prefix().'%', $this->post_id )
    );
   // var_dump($wpdb->prepare( $sql, "" ));
    //exit;

    // Sanitize user input.
    $image_ids = $_POST[ 'furrynoodles_multiple_image_attachments_ids' ];

    $order = array();
    $i = 0;
    foreach( $image_ids as $image_id )
    {
      $image_id = preg_replace( '/[^0-9]/', '', $image_id );

      $meta_key = $this->get_meta_key_prefix() . $i;
      update_post_meta( $post_id, $meta_key, $image_id );
      array_push( $order, $image_id );
      $i++;
    }
  }

  private function get_html_container()
  {
    ob_start();
    ?>
<button class="furrynoodles_multiple_image_attachments_add_image_trigger" onclick="return false;">Add Image</button>
<input id="furrynoodles_multiple_image_attachments_new_image_id" type="hidden" />
<div class="furrynoodles_multiple_image_attachments_existing_images">
<ul class="clear">%IMAGES%</ul>
</div>

<script class="furrynoodles_multiple_image_attachments_image_item_template" type="text/template">
%IMAGE_ITEM_HTML%
</script>
    
    <?php 
    $html = ob_get_clean(); 
    $wp_upload_dir = wp_upload_dir();
    $images_html = '';
    foreach( $this->get_attachments() as $attachment ){
      //var_dump($attachment);
      $images_html .= str_replace(
        array(
          '%IMAGE_FILENAME%',
          '%IMAGE_URL%',
          '%IMAGE_ID%'
        ), 
        array(
          basename($attachment['image_data']['file']),
          $wp_upload_dir['baseurl'] . '/' . dirname( $attachment['image_data']['file'] ) . '/' . $attachment['image_data']['sizes']['thumbnail']['file'],
          $attachment['post_id']
        ),
        $this->get_html_image_item( $attachment )
      );
      
    }
    $html = str_replace( '%IMAGE_ITEM_HTML%', $this->get_html_image_item(  ), $html );
    $html = str_replace( '%IMAGES%', $images_html, $html );

    return $html;
  }

  private function get_html_image_item(  )
  {
    ob_start();
    ?>
<li class="furrynoodles_multiple_image_attachments_existing_image clear">
  <img src="%IMAGE_URL%" width="100" height="100" />
  <div class="actions">
    <p class="furrynoodles_multiple_image_attachments_image_name">%IMAGE_FILENAME%</p>
    <a class="delete" href="">delete</a>
  </div>
  <input type="hidden" name="furrynoodles_multiple_image_attachments_ids[]" value="%IMAGE_ID%" />
</li> 
    <?php 
    return ob_get_clean();
  }

  public function get_attachments()
  {
    $sql .= str_replace( 
      array(
        '[[POST_ID]]',
        '[[META_KEY_PREFIX]]'
      ), 
      array(
        $this->post_id,
        $this->get_meta_key_prefix()
      ),
      '
      SELECT attachment_meta.*
      FROM wp_posts post
        LEFT JOIN wp_postmeta meta ON meta.post_id = post.ID
        AND meta.meta_key LIKE  "[[META_KEY_PREFIX]]%"
      LEFT JOIN wp_posts attachment ON meta.meta_value = attachment.ID
      LEFT JOIN wp_postmeta attachment_meta ON attachment_meta.post_id = attachment.ID
        AND attachment_meta.meta_key = "_wp_attachment_metadata"
      WHERE post.ID = [[POST_ID]]
        AND attachment_meta.meta_key IS NOT NULL
      ORDER BY meta.meta_key
      '
    );

    global $wpdb;
    $sql_images = $wpdb->get_results( $sql );

    $image_array = array();
    foreach ( $sql_images as $image )
    {
      array_push($image_array, array( 'post_id' => $image->post_id, 'image_data' => unserialize( $image->meta_value )));
    }
    return $image_array;
  }
}

if( is_admin() ){
  new Furrynoodles_Multiple_Image_Attachments();
}

/**
 *
 */
$furrynoodles_multiple_image_attachments = null;
function the_multiple_image_attachments(){
  global $furrynoodles_multiple_image_attachments;
  $mia = $furrynoodles_multiple_image_attachments;
  if( !$mia ){
    $mia = new Furrynoodles_Multiple_Image_Attachments();
    $mia->set_post_id( get_the_ID() );
  }
  return $mia->get_attachments();
}
