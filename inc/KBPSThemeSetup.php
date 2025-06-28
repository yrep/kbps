<?php
class KBPSThemeSetup {
    public function __construct() {
        // High priority
        add_action('after_setup_theme', [$this, 'theme_setup'], 1);
        add_action('after_setup_theme', [$this, 'kbps_create_footer_info_menu'], 99);
        add_action('customize_register', [$this, 'kbps_register_customizer_settings'], 20);
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
        //add_filter('get_template_part', [$this, 'kbps_remove_sidebar_from_woocommerce'], 10, 3);
        //add_filter('is_active_sidebar', [$this, 'kbps_return_false']);

        /*
        add_action('muplugins_loaded', [$this, 'mustuse_plugins_init'], 1);
        add_action('plugins_loaded', [$this, 'plugins_integration'], 5);
        // 2. Базовые настройки темы (ОБЯЗАТЕЛЬНЫЕ)
        add_action('after_setup_theme', [$this, 'theme_core_support'], 1);
        add_action('after_setup_theme', [$this, 'theme_menus'], 10);
        add_action('after_setup_theme', [$this, 'theme_textdomain'], 15);
        // 3. Регистрация компонентов
        add_action('init', [$this, 'register_post_types'], 5);
        add_action('init', [$this, 'register_taxonomies'], 6);
        add_action('init', [$this, 'custom_rewrite_rules'], 10);
        // 4. Настройки кастомайзера
        add_action('customize_register', [$this, 'customizer_settings'], 20);

        // 5. Фронтенд
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets'], 10);
        add_action('template_redirect', [$this, 'custom_redirects'], 1);
        */
    }

    /*
    function kbps_remove_sidebar_from_woocommerce($template, $slug, $name) {
        if ($slug === 'sidebar' && class_exists('WooCommerce') && (is_woocommerce() || is_product())) {
            
            return locate_template('');
        }
        return $template;
    }
    */



    public function kbps_return_false(){
            return false;
        }


    public function theme_setup() {
        $this->kbps_register_menus();
        $this->kbps_add_theme_support();
    }

    public function kbps_register_menus() {
        register_nav_menus([
            'primary' => __('Primary Menu', 'kbps-theme'),
            'footer-info' => __('Footer Info Menu', 'kbps-theme'),
        ]);
    }

    public function kbps_add_theme_support() {
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => ['site-title', 'site-description'],
        ]);
        
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('woocommerce');
    }

    public function kbps_register_customizer_settings($wp_customize) {
        // Только после theme_support!
        $wp_customize->get_section('title_tagline')->priority = 30;
        
        // Явная активация контрола логотипа
        if (current_theme_supports('custom-logo')) {
            $wp_customize->get_control('custom_logo')->section = 'title_tagline';
        }
    }
    

    public function kbps_create_footer_info_menu() {
        $menu_name = 'Footer Info Menu';
        $menu_slug = 'footer-info';

        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);

            if (!is_wp_error($menu_id)) {
                set_theme_mod('nav_menu_locations', array_merge(
                    get_theme_mod('nav_menu_locations', []),
                    [ $menu_slug => $menu_id ]
                ));

                $slugs = [
                    'obchodni-podminky',
                    'ochrana-osobnich-udaju',
                    'platebni-podminky'
                ];

                foreach ($slugs as $slug) {
                    $page = get_page_by_path($slug);
                    if ($page) {
                        wp_update_nav_menu_item($menu_id, 0, [
                            'menu-item-title'  => $page->post_title,
                            'menu-item-object' => 'page',
                            'menu-item-object-id' => $page->ID,
                            'menu-item-type'   => 'post_type',
                            'menu-item-status' => 'publish'
                        ]);
                    }
                }
            }
        }
    }

}
