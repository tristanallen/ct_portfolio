<?php

class Furrynoodles_Multiple_Image_Attachments
{
  private $unique_ref      = 'attachment';
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
    $i = 0;
    foreach( $image_ids as $image_id )
    {
      $image_id = preg_replace( '/[^0-9]/', '', $image_id );

      $meta_key = $this->get_meta_key_prefix() . $i;
      update_post_meta( $post_id, $meta_key, $image_id );
      array_push( $order, $image_id );
      $i++;
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
    $html = str_replace( '%IMAGE_ITEM_HTML%', $this->get_html_image_item( $attachment ), $html );
    return $html;
  }

  private function get_html_image_item( $attachment )
  {
    ob_start();
    ?>
<div class="furrynoodles_multiple_image_attachments_existing_image">
  <h3 class="furrynoodles_multiple_image_attachments_image_name"><?php echo basename($attachment['url']) ?></h3>
  <?php $wp_upload_dir = wp_upload_dir() ?>
  <img src="<?php echo $wp_upload_dir['baseurl'] . '/' . $attachment['url'] ?>" width="100" height="100" />
  <input type="hidden" name="furrynoodles_multiple_image_attachments_ids[]" value="%IMAGE_ID%" />
</div> 
    <?php 
    return ob_get_clean();
  }

  private function get_attachments()
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
      $image = unserialize( $image->meta_value );
      array_push($image_array, array(
        'url' => dirname( $image['file'] ) . '/' . $image['sizes']['thumbnail']['file'],
        'id'  => $image->meta_value));
    }
    return $image_array;
  }
}

new Furrynoodles_Multiple_Image_Attachments();
