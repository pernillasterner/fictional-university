<?php get_header(); ?>

<!-- SHOW POSTS -->
<?php while (have_posts()) : the_post(); ?>

<?php pageBanner(); ?>


  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i>All Campuses</a><span class="metabox__main"><?php the_title(); ?></span></p>
    </div>
    <div class="generic-content"><?php the_content(); ?></div>

    <div class="acf-map">
      <?php $mapLocation = get_field('map_locations'); ?>
      <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>">
        <h3><?php the_title(); ?></h3>
        <p><?php echo $mapLocation['address']; ?></p>
      </div>
    </div>

    <?php
      // Custom query
      $relatedPrograms = new WP_Query(array(
        'posts_per_page' => -1, // All program
        'post_type' => 'program', // Give us any program posts
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array(
          array(
            'key' => 'related_campus', // where the related campus
            'compare' => 'LIKE',
            'value' => '"' . get_the_ID() . '"', // contains the ID of the current campus that we are vieing
          )
        )
      ));

      if( $relatedPrograms->have_posts() ) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Programs available at this campus.</h2>';

      echo '<ul class="min-list link-list">';
      while( $relatedPrograms->have_posts() ) : $relatedPrograms->the_post(); ?>
        <li>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
      <?php endwhile;
      echo '</ul>';
      }
      ?>
    
    
      <?php wp_reset_postdata(); ?>
      

  </div>

<?php endwhile; ?>

<?php get_footer(); ?>
