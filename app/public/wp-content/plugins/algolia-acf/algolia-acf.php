<?php
/*
Plugin Name: Algolia ACF Composer Fields
*/

add_filter( 'algolia_post_shared_attributes', 'my_post_attributes', 10, 2 );
add_filter( 'algolia_searchable_post_shared_attributes', 'my_post_attributes', 10, 2 );

/**
 * @param array   $attributes
 * @param WP_Post $post
 *
 * @return array
 */
function my_post_attributes( array $attributes, WP_Post $post ) {

    if ( 'composer' !== $post->post_type ) {
        // We only want to add an attribute for the 'speaker' post type.
        // Here the post isn't a 'speaker', so we return the attributes unaltered.
        return $attributes;
    }

    // Get the field value with the 'get_field' method and assign it to the attributes array.
    // @see https://www.advancedcustomfields.com/resources/get_field/
    $locations = get_field( 'locations', $post->ID );

    $attributes['website'] = get_field( 'website', $post->ID );
    $attributes['locations'] = $locations;
    $attributes['years'] = get_field( 'years', $post->ID );
    $attributes['name'] = get_field( 'name', $post->ID );
    // $attributes['genres'] = get_the_terms( 'genre', $post->ID );

    $geoloc = array();
    foreach ($locations as &$value) {
      $gmap = $value['geo'];
      if (!empty($gmap)){
        $geo = array(
            "lat" => $gmap['lat'],
            "lng" => $gmap['lng']
        );
        if (!empty($geo))
          $geoloc[] = $geo;
      };
    };
    if (!empty($geoloc))
      $attributes['_geoloc'] = $geoloc;

    // Always return the value we are filtering.
    return $attributes;
}
