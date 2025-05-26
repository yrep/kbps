<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class KBPSCustomizerColors
 *
 */
class KBPSCustomizerColors {

    /**
     * Init
     */
    public static function init() {
        add_action('customize_register', [__CLASS__, 'register']);
        add_action('after_setup_theme', [__CLASS__, 'register_gutenberg_colors']);
        add_action('wp_head', [__CLASS__, 'output_css_vars']);
    }

    /**
     * Default colors
     */
    public static function get_default_colors() {
        return [
            'main'           => '#917c65',
            'secondary'      => '#726659',
            'background'     => '#ffffff',
            'text'           => '#000000',
            'text-white'     => '#ffffff',
            'text-light'     => '#7b7b7b',
            'custom-01'      => '#a9a18c',
            'custom-02'      => '#93a57d',
        ];
    }

    /**
     * Customizer
     */
    public static function register($wp_customize) {
        $colors = self::get_default_colors();

        foreach ($colors as $name => $default) {
            $wp_customize->add_setting("kbps_color_{$name}", [
                'default'   => $default,
                'transport' => 'postMessage',
                'sanitize_callback' => 'sanitize_hex_color',
            ]);

            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, "kbps_color_{$name}_control", [
                'label'    => ucwords(str_replace('-', ' ', $name)),
                'section'  => 'colors',
                'settings' => "kbps_color_{$name}",
            ]));
        }
    }

    /**
     * Gutenberg support
     */
    public static function register_gutenberg_colors() {
        $colors = self::get_default_colors();

        $palette = [];

        foreach ($colors as $name => $default) {
            $palette[] = [
                'name'  => ucwords(str_replace('-', ' ', $name)),
                'slug'  => 'kbps-' . $name,
                'color' => get_theme_mod("kbps_color_{$name}", $default),
            ];
        }

        add_theme_support('editor-color-palette', $palette);
    }

    /**
     * CSS-vars in <head>
     */
    public static function output_css_vars() {
        $colors = self::get_default_colors();

        echo '<style id="kbps-dynamic-colors">';
        echo ":root {\n";
        foreach ($colors as $name => $default) {
            $color = get_theme_mod("kbps_color_{$name}", $default);
            echo "\t--kbps-global-color-{$name}: {$color};\n";
        }
        echo "}\n";
        echo '</style>';
    }
}