<?php
class KBPSThemeAssets {
    public function __construct() {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        //add_filter('woocommerce_should_load_block_theme_styles', '__return_false');
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles'], 100);
        add_filter('wp_enqueue_scripts', [$this, 'kbps_enqueue_cake_styles'], 100);
        add_filter('wp_enqueue_scripts', [$this, 'kbps_enqueue_cake_script'], 100);
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
            'kbps-core-styles',
            KBPS_THEME_URI . 'assets/css/core-styles.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/core-styles.css')
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
            'kbps-front-wedding',
            KBPS_THEME_URI . 'assets/css/front_wedding_blocks.css',
            array('kbps-core-reset'),
            filemtime(KBPS_THEME_DIR . 'assets/css/front_wedding_blocks.css')
        );

        wp_enqueue_style(
            'kbps-woocommerce',
            KBPS_THEME_URI . 'assets/css/kbps-woocommerce.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/kbps-woocommerce.css')
        );

        wp_enqueue_style(
            'swiper',
            KBPS_THEME_URI . 'assets/css/swiper.min.css',
            [],
            filemtime(KBPS_THEME_DIR . 'assets/css/swiper.min.css')
        );
        
        // Components
        wp_enqueue_style(
            'cake-components',
            KBPS_THEME_URI . 'assets/css/components.css',
        );

    }


    function kbps_enqueue_cake_styles() {
        if ( is_singular('cake') ) {
            wp_enqueue_style(
                'cake-page-styles',
                KBPS_THEME_URI . 'assets/css/cake-page.css',
                array(),
                null
            );
        }
    }


    function kbps_enqueue_cake_script() {
        if ( is_singular('cake') ) {
            wp_enqueue_script(
                'cake-page-styles',
                KBPS_THEME_URI . 'assets/js/cake-page.js',
                array(),
                null,
                true
            );

            wp_localize_script(
                'cake-page-styles',
                'kbps_ajax_object',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
            );
        }
    }



  public function enqueueScripts() {
    // Основные скрипты
    wp_enqueue_script('jquery');
    wp_enqueue_script('wc-cart-fragments');
    wp_enqueue_script('wc-add-to-cart');
    wp_enqueue_script('woocommerce');

    // Кастомный скрипт
    wp_enqueue_script(
        'kbps-header',
        get_template_directory_uri() . '/assets/js/header.js',
        array('jquery', 'wc-cart-fragments', 'wc-add-to-cart', 'woocommerce'),
        '1.0.11', // Обновляем версию
        true
    );

    // Локализация
    wp_localize_script(
        'kbps-header',
        'kbps_cart_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kbps_cart_nonce')
        )
    );

    // Кастомные скрипты
    wp_enqueue_script(
        'custom-menu',
        KBPS_THEME_URI . '/assets/js/mobile-menu.js',
        array('jquery'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'to-top-button',
        KBPS_THEME_URI . '/assets/js/to-top-button.js',
        array('jquery'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'kbps-cake-order',
        get_template_directory_uri() . '/assets/js/cake-order.js',
        array('jquery'),
        '1.0.1',
        true
    );

    wp_enqueue_script(
        'swiper',
        get_template_directory_uri() . '/assets/js/swiper-bundle.min.js',
        array(),
        null,
        true
    );
    wp_enqueue_script(
        'kbps-front-page',
        get_template_directory_uri() . '/assets/js/front-page.js',
        array('swiper'),
        '1.0.1',
        true
    );

    // Локализация для kbps-cake-order
    wp_localize_script(
        'kbps-cake-order',
        'kbpsAjax',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kbps_order_nonce')
        )
    );

}


        /*
        wp_enqueue_script('custom-menu', KBPS_THEME_URI . '/assets/js/mobile-menu.js', array(), '1.0', true);
        wp_enqueue_script('to-top-button', KBPS_THEME_URI . '/assets/js/to-top-button.js', array(), '1.0', true);
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('wc-cart-fragments');
        
        wp_localize_script('jquery', 'kbps_cart_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));


        wp_enqueue_script(
            'kbps-cake-order',
            get_template_directory_uri() . '/assets/js/cake-order.js',
            array(),
            '1.0.1',
            true
        );

        wp_enqueue_script(
            'kbps-header',
            get_template_directory_uri() . '/assets/js/header.js',
            array('jquery'),
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

        wp_enqueue_script(
            'swiper',
            get_template_directory_uri() . '/assets/js/swiper-bundle.min.js',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'kbps-front-page',
            get_template_directory_uri() . '/assets/js/front-page.js',
            array('swiper'),
            '1.0.1',
            true
        );
        */


    public function enqueueFontAwesome() {
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            [],
            '6.0.0'
        );
    }
}