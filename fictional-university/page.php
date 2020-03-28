<?php get_header(); ?>

<!-- SHOW PAGES -->
<?php while (have_posts()) : the_post(); ?>
  <?php the_title(); ?>
  <?php the_content(); ?>
<?php endwhile; ?>

<?php get_footer(); ?>