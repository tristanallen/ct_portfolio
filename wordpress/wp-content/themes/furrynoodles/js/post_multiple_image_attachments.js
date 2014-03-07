(function( $ ){
  $(function(){

    var image_custom_uploader;
    var trigger = $('.furrynoodles_multiple_image_attachments_add_image_trigger')
    var template_image_item = $( '.furrynoodles_multiple_image_attachments_image_item_template' ).first().html();
    console.log( template_image_item );

    trigger.click(function(e) {
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
          trigger.after( html );
        });
      });

      //Open the uploader dialog
      image_custom_uploader.open();
    });


    $( "#sortable" ).sortable();
    $( "ul, li" ).disableSelection();

  });
})( jQuery );
