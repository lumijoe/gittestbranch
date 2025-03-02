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
            <!-- ACF：製品名 -->
            <!-- <h1><?php the_title(); ?></h1> -->
             <p>
                <?php the_field('product_name01'); ?><br>
                <?php the_field('product_name02'); ?><br>
                <?php the_field('product_name03'); ?><br>
            </p>

            <!-- functions.php：分野カテゴリ -->
            <p><strong>分野カテゴリ:</strong> 
                <?php 
                    $terms = get_the_terms(get_the_ID(), 'product_category');
                    if ($terms) {
                        foreach ($terms as $term) {
                            echo esc_html($term->name) . ' '; // カテゴリ名を表示
                        }
                    }
                ?>
            </p>

            <!-- functions.php：課題カテゴリ -->
            <p><strong>課題カテゴリ:</strong> 
                <?php 
                    $issues_terms = get_the_terms(get_the_ID(), 'issues');
                    if ($issues_terms && !is_wp_error($issues_terms)) {
                        foreach ($issues_terms as $issues_term) {
                            echo esc_html($issues_term->name) . ' '; // ターム名を表示
                        }
                    } else {
                        echo '課題カテゴリが設定されていません'; // タクソノミーが設定されていない場合
                    }
                ?>
            </p>


            <!-- ACF: 画像フィールド (product_image01~03) -->
            <div class="product-gallery">
                <div>
                    <?php $product_image01 = get_field('product_image01'); ?>
                    <?php if ($product_image01): ?>
                        <img src="<?php echo esc_url($product_image01['url']); ?>" 
                            alt="<?php echo esc_attr($product_image01['alt']); ?>" 
                            title="<?php echo esc_attr($product_image01['title']); ?>" 
                            width="320px" 
                            height="auto">
                    <?php endif; ?>
                </div>
                <div>
                    <?php $product_image02 = get_field('product_image02'); ?>
                    <?php if ($product_image02): ?>
                        <img src="<?php echo esc_url($product_image02['url']); ?>" 
                            alt="<?php echo esc_attr($product_image02['alt']); ?>" 
                            title="<?php echo esc_attr($product_image02['title']); ?>" 
                            width="320px" 
                            height="auto">
                    <?php endif; ?>
                </div>
                <div>
                    <?php $product_image03 = get_field('product_image03'); ?>
                    <?php if ($product_image03): ?>
                        <img src="<?php echo esc_url($product_image03['url']); ?>" 
                            alt="<?php echo esc_attr($product_image03['alt']); ?>" 
                            title="<?php echo esc_attr($product_image03['title']); ?>" 
                            width="320px" 
                            height="auto">
                    <?php endif; ?>
                </div>
            </div>

            <!-- ACF：コンテンツ説明フィールド -->
            <div class="product-items">
                <div>
                    <p>
                        <?php the_field('item01_title'); ?><br>
                        <?php the_field('item01_text'); ?><br>
                       
                    </p>
                </div>
                <div>
                    <p>
                        <?php the_field('item02_title'); ?><br>
                        <?php the_field('item02_text'); ?><br>
                       
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php endwhile; ?>

<?php get_footer(); ?>

