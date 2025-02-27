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

function create_product_taxonomy() {
    register_taxonomy(
        'product_category',  // タクソノミーのスラッグ（ACFで選択できるようになる）
        'products',  // 適用する投稿タイプ（ACFで作成した "products"）
        array(
            'label' => '分野カテゴリ',
            'rewrite' => array('slug' => 'product-category'),
            'hierarchical' => true, // true でカテゴリ型
            'show_admin_column' => true, // 管理画面の投稿一覧に表示
            'show_in_rest' => true, // Gutenberg対応
        )
    );
}
add_action('init', 'create_product_taxonomy');

function add_default_product_categories() {
    $categories = array('試験装置分野', '工業炉分野', '工具分野');

    foreach ($categories as $category) {
        if (!term_exists($category, 'product_category')) {
            wp_insert_term($category, 'product_category');
        }
    }
}
add_action('init', 'add_default_product_categories');

?>
