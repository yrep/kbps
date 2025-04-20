<?php
class KBPSThemeAssets {
    public function __construct() {
        //add_filter('woocommerce_enqueue_styles', '__return_empty_array' );
        //add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        //add_action('woocommerce_enqueue_styles', [$this, 'overrideWoocommerceStyles'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 100);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFontAwesome']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts'], 101);
        //add_action('wp_enqueue_scripts', [$this, 'enqueueMainStylesAfterWcStyles'], 800);
    }

    // public function overrideWoocommerceStyles($styles) {
    //     if (class_exists('WooCommerce')) {
    //         wp_enqueue_style(
    //             'kbps-woocommerce',
    //             KBPS_THEME_DIR . 'assets/css/kbps-woocommerce.css',
    //             ['woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen'],
    //             '1.0.0'
    //         );
    //     }
    //     return $styles;
    // }


    public function enqueueStyles() {

        // if (class_exists('WooCommerce')) {
        //     wp_dequeue_style('woocommerce-general');
        //     wp_dequeue_style('woocommerce-layout');
        //     wp_dequeue_style('woocommerce-smallscreen');
        // }


        wp_enqueue_style(
            'kbps-core-reset',
            KBPS_THEME_URI . 'assets/css/core-reset.css',
            array(),
            filemtime(KBPS_THEME_DIR . 'assets/css/core-reset.css')
        );
        
/*
        wp_enqueue_style('woocommerce-general', plugins_url() .'/woocommerce/assets/css/woocommerce-general.css', [], WC()->version);
        wp_enqueue_style('woocommerce-layout', plugins_url() .'/woocommerce/assets/css/woocommerce-layout.css', [], WC()->version);
        wp_enqueue_style('woocommerce-css', plugins_url() .'/woocommerce/assets/css/woocommerce.css', [], WC()->version);
*/
/*
        wp_enqueue_style(
            'woocommerce-general',
            plugins_url('/assets/css/woocommerce.css', WC_PLUGIN_FILE),
            [],
            WC()->version
        );
        wp_enqueue_style(
            'woocommerce-layout',
            plugins_url('/assets/css/woocommerce-layout.css', WC_PLUGIN_FILE),
            ['woocommerce-general'],
            WC()->version
        );
        wp_enqueue_style(
            'woocommerce-smallscreen',
            plugins_url('/assets/css/woocommerce-smallscreen.css', WC_PLUGIN_FILE),
            ['woocommerce-layout'],
            WC()->version
        );
*/

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

        //$this->forceLoadWcStyles();

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
            ['woocommerece', 'woocommerce-layout'],
            filemtime(KBPS_THEME_DIR . 'assets/css/kbps-woocommerce.css')
        );
        
        /*
        wp_register_style(
            'kbps-woocommerce',
            KBPS_THEME_URI . 'assets/css/kbps-woocommerce.css'
        );
	
        if ( class_exists( 'woocommerce' ) ) {
            wp_enqueue_style( 'kbps-woocommerce' );
        }
        */

        /*
        wp_enqueue_style(
            'kbps-main-styles',
            KBPS_THEME_URI . 'assets/css/main-styles.css',
            ['woocommerce-general', 'woocommerce-layout', 'kbps-core-reset'],
            filemtime(KBPS_THEME_DIR . 'assets/css/main-styles.css')
        );
        */
    }


    /**
     *  WooCommerce
     */
    /*
    protected function forceLoadWcStyles() {
        if (!class_exists('WooCommerce')) return;

        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-css');
        // wp_dequeue_style('woocommerce-general');
        // wp_dequeue_style('woocommerce-smallscreen');

        wp_enqueue_style('woocommerce-general', plugins_url() .'/woocommerce/assets/css/woocommerce-general.css');
        wp_enqueue_style('woocommerce-layout', plugins_url() .'/woocommerce/assets/css/woocommerce-layout.css');
        wp_enqueue_style('woocommerce-css', plugins_url() .'/woocommerce/assets/css/woocommerce.css');
        // wp_enqueue_style('woocommerce-smallscreen', plugins_url() .'/woocommerce/assets/css/woocommerce-smallscreen.css');
        // wp_enqueue_style('woocommerce-blocktheme', plugins_url() .'/woocommerce/assets/css/woocommerce-blocktheme.css');
        /*
        wp_enqueue_style(
            'woocommerce-general',
            plugins_url('woocommerce/assets/css/woocommerce.css', WC_PLUGIN_FILE),
            array(),
            WC_VERSION
        );
        
        wp_enqueue_style(
            'woocommerce-layout',
            plugins_url('woocommerce/assets/css/woocommerce-layout.css', WC_PLUGIN_FILE),
            array('woocommerce-general'),
            WC_VERSION
        );
        */
//    }


    public function enqueueScripts() {
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


    public function enqueueFontAwesome() {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            [],
            '6.0.0'
        );
    }
}