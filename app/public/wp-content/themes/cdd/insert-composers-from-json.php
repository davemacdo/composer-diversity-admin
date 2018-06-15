<?php
//Template Name: Insert Json from File

//Testing Mode First
$test = false;

//Add JSON to import here. Short example (3 composers) included below.
$json = '{
  "feed":[
    {"genres":["chorus","chamber","voice","electroacoustic & installation"],"medium":["string quartet"],"demographics":["West Asian/North African","Other"],"country":"USA","objectID":0,"name":"Abbasi, Anahita","living":true,"gender":"female","city/state short":"San Diego, CA","location":"San Diego, California, USA","URL":"http://anahitaabbasi.com/"},
    {"genres":["orchestra","wind band","chorus","chamber","voice","opera"],"medium":["string quartet","Pierrot ensemble"],"demographics":["White"],"country":"Australia","objectID":1,"name":"Abbott, Katy","living":true,"gender":"female","city/state short":"Melbourne","location":"Melbourne, Australia","URL":"http://www.katyabbott.com/"},
    {"genres":["jazz/improvisation","songwriting"],"medium":[],"demographics":["White"],"country":"Switzerland","objectID":2,"name":"Abbuehl, Susanne","living":true,"gender":"female","city/state short":"Lucerne","location":"Lucerne, Switzerland","URL":"http://www.susanneabbuehl.com/"}
  ]
}';

//Create array from JSON
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

      //Import to custom taxonomies
      wp_set_object_terms( $post_id, $row['genres'], 'genre' );
      wp_set_object_terms( $post_id, $row['medium'], 'medium' );
      wp_set_object_terms( $post_id, $row['demographics'], 'demographic' );
      wp_set_object_terms( $post_id, $row['gender'], 'gender' );

      //Update simple custom fields
      update_field( 'website', $row['URL'], $post_id  );

      //Create an array with location info, then add to locations array custom field
      $location = array(
        'short_name' => $row['city/state short'],
        'long_name' => $row['city/state long'],
        'country' => $row['country']
      );
      add_row('locations', $location, $post_id );


    }
  }
}
?>
