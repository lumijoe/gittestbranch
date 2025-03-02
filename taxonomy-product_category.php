<?php
get_header();
echo '<pre>';
var_dump(get_queried_object());
echo '</pre>';
get_footer();
?>

<?php
/**
 * Template Name: taxonomy-product_category
 */
get_header(); 

$term = get_queried_object(); // 現在のタクソノミー情報を取得
$term_slug = $term->slug;

$args = array(
    'post_type'      => 'products',
    'posts_per_page' => -1, // すべて取得
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_category',
            'field'    => 'slug',
            'terms'    => $term_slug, 
        ),
    ),
);

$query = new WP_Query($args);
?>

<h1><?php single_term_title(); ?> の製品一覧</h1>

<?php if ( $query->have_posts() ) : ?>
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php the_excerpt(); ?></p>
    <?php endwhile; ?>
<?php else: ?>
    <p>このカテゴリーには製品がありません。</p>
<?php endif; ?>

<?php 
wp_reset_postdata();
get_footer(); 
?>

