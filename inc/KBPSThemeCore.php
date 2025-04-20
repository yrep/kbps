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
        add_filter('upload_mimes', [$this, 'allowSvgUpload']);
        add_filter('wp_check_filetype_and_ext', [$this, 'allowSvgUse'],  10, 4);
        add_action('after_switch_theme', [$this, 'createDefaultPages']);
        add_action('after_switch_theme', [$this, 'createWoocommerceTags']);
    }

    public function getClass( $key ) {
        return $this->classes[ $key ] ?? '';
    }

    public function getClassAttr( $keys ) {
        $classes = is_array( $keys ) ? array_map( [ $this, 'getClass' ], $keys ) : [ $this->getClass( $keys ) ];
        echo 'class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"';
    }

    public function allowSvgUpload( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function allowSvgUse($data, $file, $filename, $mimes) {
        if (strpos($filename, '.svg') !== false) {
            $data['type'] = 'image/svg+xml';
            $data['ext']  = 'svg';
        }
        return $data;
    }

    /**
     * Creates default pages from configuration
     * 
     * @return array|bool Array of results or false on failure
     */
    public function createDefaultPages() {
        $results = [];
        $page_manager = new KBPSPageManager();
        $pages = include KBPS_THEME_DIR . 'config/default-pages.php';
    
        foreach ($pages as $page_config) {
            $page = $this->findExistingPage($page_config);
            
            if ($page) {
                $result = [
                    'status' => 'exists',
                    'page_id' => $page->ID,
                    'template' => get_page_template_slug($page->ID)
                ];
            } else {
                $result = $this->createSinglePage($page_config, $page_manager);
            }
    
            // Configure WooCommerce
            if (isset($page_config['is_woocommerce_shop']) && $page_config['is_woocommerce_shop']) {
                $page_id = $result['page_id'] ?? null;
                if ($page_id) {
                    $this->setupWooCommerceShopPage($page_id);
                    $result['woocommerce_configured'] = true;
                }
            }
    
            $results[$page_config['title']] = $result;
        }
        
        return $results;
    }

    /**
     * Finds existing page by slug or title
     */
    protected function findExistingPage($config) {
        $page_by_slug = get_page_by_path($config['slug']);
        if ($page_by_slug) return $page_by_slug;

        return get_page_by_title($config['title']);
    }

    /**
     * Configures page as WooCommerce shop
     */
    protected function setupWooCommerceShopPage($page_id) {
        if (!class_exists('WooCommerce')) return false;
    
        // 1. Устанавливаем страницу магазина в настройках WooCommerce
        update_option('woocommerce_shop_page_id', $page_id);
        
        // 2. Удаляем любые кастомные шаблоны страницы
        delete_post_meta($page_id, '_wp_page_template');
        
        // 3. Добавляем шорткод товаров если контент пустой
        if (empty(get_post_field('post_content', $page_id))) {
            wp_update_post([
                'ID' => $page_id,
                'post_content' => '<!-- wp:woocommerce/all-products -->'
            ]);
        }
        
        // 4. Чистим кэш rewrite rules
        flush_rewrite_rules(false);
        
        return true;
    }

    /**
     * Creates single page from configuration
     * 
     * @param array $page_config Page configuration
     * @param KBPSPageManager $page_manager Page manager instance
     * @return array|WP_Error Creation result or error
     */
    protected function createSinglePage($page_config, $page_manager) {
        if (empty($page_config['title'])) {
            return new WP_Error('invalid_config', 'Page title is required');
        }

        $page_config = wp_parse_args($page_config, [
            'slug' => sanitize_title($page_config['title']),
            'content' => '',
            'template' => '',
            'status' => 'publish',
            'parent' => 0
        ]);

        if ($page_manager->getPageBySlug($page_config['slug']) || 
            $page_manager->getPageByTitle($page_config['title'])) {
            return ['status' => 'exists'];
        }

        $page_id = $page_manager->addPage($page_config);
        
        return is_wp_error($page_id) ? $page_id : [
            'status' => 'created',
            'page_id' => $page_id,
            'template' => $page_config['template']
        ];
    }

    /**
     * Create WooCommerce product tags with Czech localization
     */
    public function createWoocommerceTags() {
        if (!taxonomy_exists('product_tag')) {
            return false;
        }

        $tags_config = require_once KBPS_THEME_DIR . 'config/default-tags.php';
        $results = [];

        foreach ($tags_config as $slug => $tag_data) {
            $term = term_exists($slug, 'product_tag');
            
            if (!$term) {
                $result = wp_insert_term(
                    $tag_data['name'],
                    'product_tag',
                    [
                        'slug' => $slug,
                        'description' => $tag_data['description'] ?? ''
                    ]
                );

                if (!is_wp_error($result) && isset($tag_data['image'])) {
                    $this->setTagImage($result['term_id'], $tag_data['image']);
                }

                $results[$slug] = is_wp_error($result) ? $result : 'created';
            } else {
                // Обновляем описание если тег уже существует
                wp_update_term($term['term_id'], 'product_tag', [
                    'description' => $tag_data['description'] ?? ''
                ]);
                $results[$slug] = 'updated';
            }
        }
        
        KBPSLogger::get_instance()->info($results, 'Tags created:');
        //$this->log_tag_creation($results);
        return $results;
    }

    /**
     * Set image for product tag (requires WooCommerce 3.6+)
     */
    protected function setTagImage($term_id, $image_path) {
        $image_id = attachment_url_to_postid(get_theme_file_uri($image_path));
        if ($image_id) {
            update_term_meta($term_id, 'thumbnail_id', $image_id);
        }
    }


}


