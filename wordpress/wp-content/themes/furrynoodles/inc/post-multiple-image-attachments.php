<?php

class Furrynoodles_Multiple_Image_Attachments
{
  private $meta_box_id     = 'furrynoodles_multiple_image_attachments';
  private $meta_box_title  = 'Images';
  private $post_type       = 'work';
  private $nonce_action    = 'furrynoodles_multiple_image_attachments';
  private $nonce_name      = 'furrynoodles_multiple_image_attachments_add_image';
  private $meta_key_prefix = '_attachment';
  private $post_id;

  public function __construct()
  {
    //posts_join does not work in admin...
    add_filter( 'posts_join', array( $this, 'query_add_join_attachments' ) );

    if( is_admin() ){
      add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
      add_action( 'the_post', array( $this, 'on_post_ready' ) );
      add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
      add_action( 'save_post', array( $this, 'save' ) );
    }
  }

  public function on_post_ready( $post )
  {
    exit( 'id'.$post->ID );
  }

  public function query_add_join_attachments( $query )
  {
    if( get_post_type() !== $this->post_type )
      return $query;

    //todo code in the join...
  }

  public function enqueue_scripts( $hook )
  {
    $hooks = array('post.php', 'edit.php');
    if( !in_array( $hook, $hooks ) ) return;

    wp_enqueue_script( 'furrynoodles_multiple_image_attachments_js',
                       get_template_directory_uri() . '/js/post_multiple_image_attachments.js',
                       array( 'jquery' )
                       );
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
    echo str_replace( '%IMAGES%', 'TEST', $this->get_html_container() );
  }

  public function save( $post_id )
  {
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

    // Sanitize user input.
    $image_ids = $_POST[ 'furrynoodles_multiple_image_attachments_ids' ];

    $order = array();
    foreach( $image_ids as $image_id )
    {
      $image_id = preg_replace( '/[^0-9]/', '', $image_id );

      $meta_key = $this->meta_key_prefix.'_'.$i;
      update_post_meta( $post_id, $meta_key, $image_id );
      array_push( $order, $image_id );
    }
    update_post_meta( $post_id, $meta_key . '_order', serialize( $order ) );
  }

  private function get_html_container()
  {
    ob_start();
    ?>
<button class="furrynoodles_multiple_image_attachments_add_image_trigger" onclick="return false;">Add Image</button>
<input id="furrynoodles_multiple_image_attachments_new_image_id" type="hidden" />
<div class="furrynoodles_multiple_image_attachments_existing_images">
%IMAGES%
</div>

<script class="furrynoodles_multiple_image_attachments_image_item_template" type="text/template">
%IMAGE_ITEM_HTML%
</script>
    <?php 
    $html = ob_get_clean(); 
    foreach( $this->get_attachments() as $attachment ){
      $html .= $this->get_html_image_item( $attachment );
    }
    $html = str_replace( '%IMAGE_ITEM_HTML%', $this->get_html_image_item(), $html );
    return $html;
  }

  private function get_html_image_item( $attachment )
  {
    ob_start();
    ?>
<div class="furrynoodles_multiple_image_attachments_existing_image">
  <h3 class="furrynoodles_multiple_image_attachments_image_name">%IMAGE_FILENAME%</h3>
  <img src="%IMAGE_URL%" width="100" height="100" />
  <input type="hidden" name="furrynoodles_multiple_image_attachments_ids[]" value="%IMAGE_ID%" />
</div> 
    <?php 
    return ob_get_clean();
  }

  private function get_attachments()
  {
    return array(
      array( 'url' => '',
             'id'  => '21'
      ),
      array( 'url' => '',
             'id'  => '23'
      ),
      array( 'url' => '',
             'id'  => '26'
      )
    );
  }
}

new Furrynoodles_Multiple_Image_Attachments();
