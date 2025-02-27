<?php
/**
 * Template Name: taxonomy-product_category
 * Description: This is the template 
 */
get_header(); ?>
<h1><?php single_term_title(); ?> の製品一覧</h1>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p><?php the_excerpt(); ?></p>
<?php endwhile; endif; ?>
<?php get_footer(); ?>



