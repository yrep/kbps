<?php
class KBPSThemeAssets {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_font_awesome']);
        add_action('wp_enqueue_scripts', [$this, 'mytheme_enqueue_scripts']);
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'kbps-core-reset',
            KBPS_THEME_URI . 'assets/css/core-reset.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/core-reset.css')
        );

        wp_enqueue_style(
            'kbps-core-variables',
            KBPS_THEME_URI . 'assets/css/core-variables.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/core-variables.css')
        );
        
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
            ['kbps-core-reset'],
            filemtime(KBPS_THEME_DIR . 'assets/css/main-styles.css')
        );


    }

    public function mytheme_enqueue_scripts() {
        wp_enqueue_script('custom-menu', KBPS_THEME_URI . '/assets/js/mobile-menu.js', array(), '1.0', true);
        wp_enqueue_script('to-top-button', KBPS_THEME_URI . '/assets/js/to-top-button.js', array(), '1.0', true);
        
        /*
        wp_enqueue_script(
            'cake-order-script', 
            KBPS_THEME_URI . '/assets/js/cake-order.js', 
            [], 
            '1.0', 
            true
        );

        wp_localize_script('cake-order-script', 'cakeOrderVars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cake_order_nonce')
        ]);
        */
        wp_enqueue_script(
            'kbps-cake-order',
            get_template_directory_uri() . '/assets/js/cake-order.js',
            array(),
            '1.0.1', // Обновите версию для сброса кэша
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


    public function enqueue_font_awesome() {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            [],
            '6.0.0'
        );
    }
}