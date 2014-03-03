<?php

// Callback function to insert 'styleselect' into the $buttons array
function furryfool_editor_formatting_keyline_mce_buttons_2( $buttons ) {
  array_unshift( $buttons, 'styleselect' );
  return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'furryfool_editor_formatting_keyline_mce_buttons_2');

// Callback function to filter the MCE settings
function furryfool_editor_formatting_keyline_mce_before_init_insert_formats( $init_array ) {  
  // Define the style_formats array
  $style_formats = array(  
    // Each array child is a format with it's own settings
    array(  
      'title'   => '.keyline',  
      'inline'  => 'span',  
      'classes' => 'keyline',
      'wrapper' => true,
      
    ),  
  );  
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode( $style_formats );  
  
  return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'furryfool_editor_formatting_keyline_mce_before_init_insert_formats' ); 

function furryfool_editor_formatting_keyline_theme_add_editor_styles() {
      add_editor_style( 'style.css' );
}
add_action( 'init', 'furryfool_editor_formatting_keyline_theme_add_editor_styles' );
