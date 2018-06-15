<?php
//Template Name: Insert Json from File

//Testing Mode First
$test = false;

$json = '{
  }';

$array = json_decode($json, true);

if($array) {
  if($test){  //Testing mode simply outputs the data onto the screen to verify
    foreach ($array[feed] as $row){ //Loop through the feed and output each row
      echo 'Name:' . $row['name'] . '<br>';
      echo 'Genres:' . join(", ",$row['genres']) . '<br>';
    }
  } else { //Live mode actually inserts the post into our database
    foreach ($array[feed] as $row) { //Loop through the feed so we can insert each post
      $post_arr = array(
        'post_title' => $row['name'], //Title of post
        'post_type' =>'composer', //could be any custom post type
        'post_status' => 'publish'
      );
      $post_id = wp_insert_post($post_arr, true);
      wp_set_object_terms( $post_id, $row['genres'], 'genre' );
      wp_set_object_terms( $post_id, $row['medium'], 'medium' );
      wp_set_object_terms( $post_id, $row['demographics'], 'demographic' );
      wp_set_object_terms( $post_id, $row['gender'], 'gender' );
      // update_field( 'living', $row['living'], $post_id  );
      update_field( 'website', $row['URL'], $post_id  );
      $location = array(
        'short_name' => $row['city/state short'],
        'long_name' => $row['city/state long'],
        'country' => $row['country']
      );
      add_row('locations', $location, $post_id );
      // update_field( 'location_country', $row['country'], $post_id  );
      // update_field( 'location_short_name', $row['city/state short'], $post_id  );
      // update_field( 'location_long_name', $row['city/state long'], $post_id  );

    }
  }
}
?>