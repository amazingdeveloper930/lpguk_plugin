<?php
/*


function my_awesome_func( $data ) {
  $posts = get_posts( array(
    'author' => $data['id'],
  ) );
 
  if ( empty( $posts ) ) {
    return null;
  }
 
  return $posts[0]->post_title;
}


<?php
add_action( 'rest_api_init', function () {
  register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
  ) );
} );


*/
//prepare_items
function get_Location( $data )
{
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'header_info';
    
    
    $locations = $wpdb -> get_results("select * from " . $table_name . " where upper(domain) = upper('" . $data['domain'] . "')");
    if( empty ($locations))
    {
        return null;
    }
    return array('county' => $locations[0] -> county, 'price' => $locations[0] -> price);
}

add_action('rest_api_init', function() {
   register_rest_route('wp/v2', '/domain/(?P<domain>[a-zA-Z0-9-.]+)', array(
       'methods' => 'GET',
       'callback' => 'get_Location'
       )); 
});