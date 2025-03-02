<?php
// ACFや管理画面設定
function create_product_taxonomy() {
    // タクソノミー：課題
    register_taxonomy(
        'issues',  // タクソノミーのスラッグ
        'products',  // 適用する投稿タイプ
        array(
            'label'             => '課題',
            'rewrite'           => array('slug' => 'issues'),
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
        )
    );

    // タクソノミー：分野
    register_taxonomy(
        'product_category',  // タクソノミーのスラッグ（ACFで選択可能に）
        'products',  // 適用する投稿タイプ（ACF作成"products"）
        array(
            'label'             => '分野カテゴリ',
            'rewrite'           => array('slug' => 'product-category'),
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
        )
    );

    
}
add_action('init', 'create_product_taxonomy');

// 初期カテゴリ設定（スラッグ指定）
function add_default_product_categories() {
    // タクソノミーのターム：課題
    $issues = array(
        '脱炭素化' => 'decarbonization',
        'ICT/IOT化' => 'ictiot',
        '現場改善' => 'improvement',
    );

    foreach ($issues as $name => $slug) {
        if (!term_exists($name, 'issues')) {
            wp_insert_term($name, 'issues', array('slug' => $slug));
        }
    }

    // タクソノミーのターム：分野
    $categories = array(
        '試験装置分野' => 'testdevice',
        '工業炉分野'  => 'furnace',
        '工具分野'    => 'tool',
    );

    foreach ($categories as $name => $slug) {
        if (!term_exists($name, 'product_category')) {
            wp_insert_term($name, 'product_category', array('slug' => $slug));
        }
    }

    
}
add_action('init', 'add_default_product_categories');


// 製品のカスタム投稿タイプ登録
function custom_register_post_type() {
    register_post_type('products',
        array(
            'label'         => '製品登録',
            'public'        => true,
            'has_archive'   => true,
            'rewrite'       => array('slug' => 'products/%product_category%', 'with_front' => false),
            'menu_position' => 1,
            'taxonomies'    => array('product_category', 'issues'), // 課題のタクソノミーも追加
        )
    );
}
add_action('init', 'custom_register_post_type');

// 製品のパーマリンク構造を修正
function custom_products_permalinks($permalink, $post) {
    if ($post->post_type == 'products') {
        $terms = get_the_terms($post->ID, 'product_category');
        if ($terms && !is_wp_error($terms)) {
            $term_slug = array_shift($terms)->slug;
            return str_replace('%product_category%', $term_slug, $permalink);
        }
    }
    return $permalink;
}
add_filter('post_type_link', 'custom_products_permalinks', 10, 2);

// リライトルールを追加
function custom_rewrite_rules() {
    add_rewrite_rule(
        '^products/([^/]+)/?$',
        'index.php?post_type=products&name=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^products/?$',
        'index.php?post_type=products',
        'top'
    );
    add_rewrite_rule(
                '^products/([^/]+)/?$',
                'index.php?product_category=$matches[1]',
                'top'
            );
            add_rewrite_rule(
                '^products/?$',
                'index.php?post_type=products',
                'top'
            );
}
add_action('init', 'custom_rewrite_rules');

// テーマ有効化・切り替え時にリライトルールを更新
function flush_rewrite_rules_on_activation() {
    custom_register_post_type();
    custom_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');

function flush_rewrite_rules_on_theme_switch() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_switch');



?>