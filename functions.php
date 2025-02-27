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
?>
