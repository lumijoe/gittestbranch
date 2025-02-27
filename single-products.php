<?php
/**
 * Template Name: single-products
 * Description: This is the template 
 */
get_header(); 

// 2. 現在の投稿を取得（製品ページ）
while (have_posts()) : the_post(); ?>

<main>
    <section>
        <div class="product-details">
            <!-- 製品名 -->
            <h1><?php the_title(); ?></h1>

            <!-- 価格 -->
            <p><strong>価格:</strong> <?php the_field('price'); ?> 円</p>

            <!-- メーカー -->
            <p><strong>メーカー:</strong> <?php the_field('manufacturer'); ?></p>

            <!-- 発売日 -->
            <p><strong>発売日:</strong> <?php the_field('release_date'); ?></p>

            <!-- 分野カテゴリ -->
            <p><strong>分野カテゴリ:</strong> 
                <?php 
                    $terms = get_the_terms(get_the_ID(), 'product_category');
                    if ($terms) {
                        foreach ($terms as $term) {
                            echo $term->name . ' '; // カテゴリ名を表示
                        }
                    }
                ?>
            </p>

            <!-- 画像コンテンツ（oEmbedの場合） -->
            <div class="product-image">
                <?php $product_image = get_field('product_image'); ?>
                <?php if ($product_image): ?>
                    <div class="embed-container">
                        <?php echo wp_oembed_get($product_image); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php endwhile; ?>

<?php get_footer(); ?>

