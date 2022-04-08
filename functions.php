<?php
/* Adds page name to classes for page */
add_filter('body_class','page_class');
function page_class($classes) {
   global $wp_query;
   $page = '';
   $page = $wp_query->query_vars['pagename'];
   // add 'pagename' to the $classes array
   $classes[] = $page;
   // return the $classes array
   return $classes;
}
/* Add course tab */
add_filter( 'wpsl_meta_box_fields', 'custom_meta_box_fields' );
function custom_meta_box_fields( $meta_fields ) {
	
	$meta_fields[__( 'Courses', 'wpsl' )] = array(
        'course_title' => array(
            'label' => __( 'Course Title', 'wpsl' )
        )
    );

    return $meta_fields;
}
/* Add field to JSON response */
add_filter( 'wpsl_frontend_meta_fields', 'custom_frontend_meta_fields' );

function custom_frontend_meta_fields( $store_fields ) {

    $store_fields['wpsl_course_title'] = array( 
        'name' => 'course_title',
        'type' => 'text'
    );

    return $store_fields;
}
/* Show the output in search results */
add_filter( 'wpsl_listing_template', 'custom_listing_template' );

function custom_listing_template() {

    global $wpsl_settings;

    $listing_template = '<li data-store-id="<%= id %>">' . "\r\n";
    $listing_template .= "\t\t" . '<div>' . "\r\n";
    $listing_template .= "\t\t\t" . '<p><%= thumb %>' . "\r\n";
    $listing_template .= "\t\t\t\t" . wpsl_store_header_template( 'listing' ) . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span class="wpsl-street"><%= address %></span>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<% if ( address2 ) { %>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span class="wpsl-street"><%= address2 %></span>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<% } %>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span>' . wpsl_address_format_placeholders() . '</span>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span class="wpsl-country"><%= country %></span>' . "\r\n";
    $listing_template .= "\t\t\t" . '</p>' . "\r\n";
    
    // Check if the 'appointment_url' contains data before including it.
    $listing_template .= "\t\t\t" . '<% if ( course_title ) { %>' . "\r\n";
    $listing_template .= "\t\t\t" . '<p>Course Title: <%= course_title %>></p>' . "\r\n";
    $listing_template .= "\t\t\t" . '<% } %>' . "\r\n";
    
    $listing_template .= "\t\t" . '</div>' . "\r\n";

    // Check if we need to show the distance.
    if ( !$wpsl_settings['hide_distance'] ) {
        $listing_template .= "\t\t" . '<%= distance %> ' . esc_html( $wpsl_settings['distance_unit'] ) . '' . "\r\n";
    }
 
    $listing_template .= "\t\t" . '<%= createDirectionUrl() %>' . "\r\n"; 
    $listing_template .= "\t" . '</li>' . "\r\n"; 

    return $listing_template;
}
?>