(function( $ ){
  $(function(){

    var image_custom_uploader;
    var trigger = $('.furrynoodles_multiple_image_attachments_add_image_trigger');
    var image_container = $('.furrynoodles_multiple_image_attachments_existing_images ul');
    var template_image_item = $( '.furrynoodles_multiple_image_attachments_image_item_template' ).first().html();
    //console.log( template_image_item );

    $('body').on("click", ".furrynoodles_multiple_image_attachments_add_image_trigger", function(e) {
      e.preventDefault();

      //If the uploader object has already been created, reopen the dialog
      if (image_custom_uploader) {
        image_custom_uploader.open();
        return;
      }

      //Extend the wp.media object
      image_custom_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose or Upload Images',
        button: {
          text: 'Apply'
        },
        multiple: true
      });

      //When a file is selected, grab the URL and set it as the text field's value
      image_custom_uploader.on( 'select', function() {
        var attachments = image_custom_uploader.state().get('selection').toJSON();
        $( attachments ).each(function(){
          var attachment = this;
          console.log( attachment );
          var html = template_image_item.replace( '%IMAGE_FILENAME%', attachment.filename );
          html = html.replace( '%IMAGE_URL%', attachment.url );
          html = html.replace( '%IMAGE_ID%', attachment.id );
          image_container.append( html );
        });
      });

      //Open the uploader dialog
      image_custom_uploader.open();
    });


    $( ".furrynoodles_multiple_image_attachments_existing_images ul" ).sortable();
    $( ".furrynoodles_multiple_image_attachments_existing_images ul, .furrynoodles_multiple_image_attachments_existing_images li" ).disableSelection();


    $('body').on("click", ".furrynoodles_multiple_image_attachments_existing_image .delete", function(e) {
      e.preventDefault();
      //console.log("")


      $(this).parents(".furrynoodles_multiple_image_attachments_existing_image").remove();

    });

  });
})( jQuery );
