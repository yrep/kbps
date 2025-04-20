<?php
class KBPSThemeAssets {
    public function __construct() {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        //add_filter('woocommerce_should_load_block_theme_styles', '__return_false');
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 100);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFontAwesome']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts'], 101);
    }


    public function enqueueStyles() {

        wp_enqueue_style(
            'kbps-core-reset',
            KBPS_THEME_URI . 'assets/css/core-reset.css',
            array(),
            filemtime(KBPS_THEME_DIR . 'assets/css/core-reset.css')
        );

        wp_enqueue_style(
            'kbps-core-variables',
            KBPS_THEME_URI . 'assets/css/core-variables.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/core-variables.css')
        );
        
        wp_enqueue_style('woocommerce-layout', plugins_url() .'/woocommerce/assets/css/woocommerce-layout.css', [], WC()->version);
        wp_enqueue_style('woocommerce-smallscreen', plugins_url() .'/woocommerce/assets/css/woocommerce-smallscreen.css', ['woocommerce-layout'], WC()->version, 'only screen and (max-width: 768px)');
        wp_enqueue_style('woocommerce-general', plugins_url() .'/woocommerce/assets/css/woocommerce.css', ['woocommerce-layout'], WC()->version);
        wp_enqueue_style('woocommerce-block-style', plugins_url('/woocommerce/assets/css/woocommerce-blocktheme.css'), [], WC()->version);

        wp_enqueue_style(
            'kbps-core-typography',
            KBPS_THEME_URI . 'assets/css/core-typography.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/core-typography.css')
        );

        wp_enqueue_style(
            'kbps-header-styles',
            KBPS_THEME_URI . 'assets/css/header-styles.css',
            ['kbps-core-reset'],
            filemtime(KBPS_THEME_DIR . 'assets/css/header-styles.css')
        );

        wp_enqueue_style(
            'kbps-footer-styles',
            KBPS_THEME_URI . 'assets/css/footer-styles.css',
            ['kbps-core-reset'],
            filemtime(KBPS_THEME_DIR . 'assets/css/footer-styles.css')
        );

        wp_enqueue_style(
            'kbps-main-styles',
            KBPS_THEME_URI . 'assets/css/main-styles.css',
            array('kbps-core-reset'),
            filemtime(KBPS_THEME_DIR . 'assets/css/main-styles.css')
        );

        wp_enqueue_style(
            'kbps-woocommerce',
            KBPS_THEME_URI . 'assets/css/kbps-woocommerce.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/kbps-woocommerce.css')
        );
        
    }


    public function enqueueScripts() {
        wp_enqueue_script('custom-menu', KBPS_THEME_URI . '/assets/js/mobile-menu.js', array(), '1.0', true);
        wp_enqueue_script('to-top-button', KBPS_THEME_URI . '/assets/js/to-top-button.js', array(), '1.0', true);
        
        wp_enqueue_script(
            'kbps-cake-order',
            get_template_directory_uri() . '/assets/js/cake-order.js',
            array(),
            '1.0.1',
            true
        );
 
        wp_localize_script(
            'kbps-cake-order',
            'kbpsAjax',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('kbps_order_nonce')
            )
        );

}


    public function enqueueFontAwesome() {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            [],
            '6.0.0'
        );
    }
}