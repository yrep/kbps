<?php
class KBPSThemeSetup {
    public function __construct() {
        // High priority
        add_action('after_setup_theme', [$this, 'theme_setup'], 1);
        add_action('customize_register', [$this, 'kbps_register_customizer_settings'], 20);
        

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

    public function theme_setup() {
        $this->kbps_register_menus();
        $this->kbps_add_theme_support();
    }

    public function kbps_register_menus() {
        register_nav_menus([
            'primary' => __('Primary Menu', 'kbps-theme'),
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
}
