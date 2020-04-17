<?php

// ADD DIFFERENT B-IMG TO PAGE BANNER
function pageBanner( $args = NULL ) { 

  // Faulback if no title is provided
  if (!isset($args['title'])) {
    $args['title'] = get_the_title(); // Value in the databas
  }
  
  if (!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!isset($args['photo'])) {
    if(get_field('page_banner_background_image')) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }
?>

  <?php $pageBannerImage = get_field('page_banner_background_image'); ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo'] ?>);"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>  
    </div>

<?php }



// LOAD FILES
function university_files() {
  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyD8doPNa5koEe4xrrZbzzmBsCo3Mc3MZPc', NULL, microtime(), true);
  wp_enqueue_script('google-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
}
add_action('wp_enqueue_scripts', 'university_files');


// THEME SUPPORT
function university_features() {
  // register_nav_menu('headerMenuLocation', 'Header Menu Location');
  // register_nav_menu('footerLocationOne', 'Footer Location One');
  // register_nav_menu('footerLocationTwo', 'Footer Location Two');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, array('left', 'top'));
  add_image_size('professorPortrait', 480, 650, true); // true == is aloud to crop
  add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

// Adjusting main query
function university_adjust_queries($query) {

  if( !is_admin() && is_post_type_archive('campus') && $query->is_main_query() ) {
    $query->set('posts_per_page', -1); // Pull in all pins in the google map
  }

  if( !is_admin() && is_post_type_archive('program') && $query->is_main_query() ) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1); // Pull in all posts
  }

  if( !is_admin() && is_post_type_archive('event') && $query->is_main_query() ) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      )
    ));
  }
}

add_action( 'pre_get_posts', 'university_adjust_queries' );


// SET THE GOOGLE MAP API
function universityMapKey($api) {
  $api['key'] = 'AIzaSyD8doPNa5koEe4xrrZbzzmBsCo3Mc3MZPc';
  return $api;
}
/*
The first argument targets the acf plugin and let it know we have a google maps api key 
*/
add_filter( 'acf/fields/google_map/api', 'universityMapKey' );

