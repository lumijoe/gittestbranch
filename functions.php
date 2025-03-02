<?php
function theme_enqueue_assets() {
    // WordPress の jQuery を登録（必要なら無効化してCDN版を使うことも可）
    wp_enqueue_script('jquery');

    // Bootstrap CSS
    wp_enqueue_style('bootstrap',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    array(), '5.3.0');

    // メインCSS
    wp_enqueue_style('main-style',
    get_template_directory_uri() . '/assets/scss/home.css',
    array(), '1.0.0');

    // Bootstrap JS（jQueryに依存）
    wp_enqueue_script('bootstrap-js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    array('jquery'), '5.3.0', true);

    // Product.js（jQueryに依存）
    // wp_enqueue_script('product-js',
    // get_template_directory_uri() . '/assets/js/product.js',
    // array('jquery'), '1.0.0', true);

    // メインJS（最後に読み込ませる）
    wp_enqueue_script('main-js',
    get_template_directory_uri() . '/assets/js/main.js',
    array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets');

function my_theme_setup() {
    register_nav_menus(array(
        'main_menu' => 'メインメニュー'
    ));
}
add_action('after_setup_theme', 'my_theme_setup');

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
