<?php

class KBPSThemeCore {
    
    private $classes = [
        'main_wrapper' => 'kbps-main-wrapper',
        'primary'      => 'kbps-primary',
        'secondary'    => 'kbps-secondary',
        'container'    => 'kbps-container',
        'button'       => 'kbps-btn',
        'active'       => 'kbps-active',
        'hidden'       => 'kbps-hidden',
    ];
    
    
    public function __construct() {
        add_filter('upload_mimes', [$this, 'kbps_allow_svg_upload']);
        add_filter('wp_check_filetype_and_ext', [$this, 'kbps_allow_svg_use'],  10, 4);
        add_action('after_switch_theme', [$this, 'kbps_create_required_pages']);
    }

    public function getClass( $key ) {
        return $this->classes[ $key ] ?? '';
    }

    public function getClassAttr( $keys ) {
        $classes = is_array( $keys ) ? array_map( [ $this, 'getClass' ], $keys ) : [ $this->getClass( $keys ) ];
        echo 'class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"';
    }

    public function kbps_allow_svg_upload( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function kbps_allow_svg_use($data, $file, $filename, $mimes) {
        if (strpos($filename, '.svg') !== false) {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = 'svg';
        }
        return $data;
    }


    public function kbps_create_required_pages() {
        $pages = array(
            array('title' => 'Svatební dorty',      'slug' => 'svatebni-dorty'),
            array('title' => 'Vánoční nabídka',     'slug' => 'vanocni-nabidka'),
            array('title' => 'Dorty',               'slug' => 'dorty'),
            array('title' => 'Degustační box',      'slug' => 'degustacni-box'),
            array('title' => 'Informace',           'slug' => 'informace'),
            array('title' => 'Cukrárna',            'slug' => 'cukrarna'), // Магазин
        );
    
        foreach ($pages as $page) {
            $slug  = $page['slug'];
            $title = $page['title'];
    
            // Проверка: есть ли уже такая страница
            $existing_page = get_page_by_path($slug);
            if (!$existing_page) {
                $page_id = wp_insert_post(array(
                    'post_title'   => $title,
                    'post_name'    => $slug,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_content' => '',
                ));
            } else {
                $page_id = $existing_page->ID;
            }
    
            // Если страница создана/существует
            if ($page_id && !is_wp_error($page_id)) {
                // Если это Cukrárna — делаем её страницей магазина WooCommerce
                if ($slug === 'cukrarna') {
                    update_option('woocommerce_shop_page_id', $page_id);
                } else {
                    // Назначаем кастомный шаблон по slug
                    $template_file = 'templates/template-' . $slug . '.php';
                    update_post_meta($page_id, '_wp_page_template', $template_file);
                }
            }
        }
    }

}


