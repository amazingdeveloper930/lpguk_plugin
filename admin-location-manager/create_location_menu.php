<?php


function custom_location_menu() { 
 
  add_menu_page( 
      'Location Management', 
      'Location Management', 
      'administrator', 
      '_location_menu_slug', 
      'custom_location_callback', 
      'dashicons-admin-site-alt' 
 
     );
}

add_action('admin_menu', 'custom_location_menu');

?>