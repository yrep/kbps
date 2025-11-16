<?php
/**
 * KBPSRecipePostType - Custom post type for recipes and courses
 */

class KBPSRecipePostType {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_kbps_recipe', array($this, 'save_file_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('Recipes & Courses', 'kbps'),
            'singular_name'      => __('Recipe/Course', 'kbps'),
            'menu_name'          => __('Recipes & Courses', 'kbps'),
            'name_admin_bar'     => __('Recipe/Course', 'kbps'),
            'add_new'            => __('Add New', 'kbps'),
            'add_new_item'       => __('Add New Recipe/Course', 'kbps'),
            'edit_item'          => __('Edit Recipe/Course', 'kbps'),
            'new_item'           => __('New Recipe/Course', 'kbps'),
            'view_item'          => __('View Recipe/Course', 'kbps'),
            'all_items'          => __('All Recipes & Courses', 'kbps'),
            'search_items'       => __('Search Recipes & Courses', 'kbps'),
            'not_found'          => __('No recipes or courses found', 'kbps'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'capability_type'    => 'post',
            'rewrite'            => ['slug' => 'recipe'],
            'supports'           => ['title', 'editor', 'thumbnail'],
            'show_in_rest'       => true,
            'has_archive'        => true,
        );

        register_post_type('kbps_recipe', $args);
    }

    public function add_admin_menu() {
        add_submenu_page(
            'kbps_menu',
            __('Recipes & Courses', 'kbps'),
            __('Recipes & Courses', 'kbps'),
            'manage_options',
            'edit.php?post_type=kbps_recipe'
        );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'kbps_recipe_file',
            __('Recipe File', 'kbps'),
            array($this, 'render_file_meta_box'),
            'kbps_recipe',
            'side',
            'high'
        );
    }

    public function render_file_meta_box($post) {
        wp_nonce_field('kbps_recipe_file', 'kbps_recipe_file_nonce');
        
        $current_file = get_post_meta($post->ID, '_recipe_file_url', true);
        $current_file_id = get_post_meta($post->ID, '_recipe_file_id', true);
        ?>
        <div class="kbps-file-upload">
            <input type="hidden" id="kbps_recipe_file_id" name="kbps_recipe_file_id" value="<?php echo esc_attr($current_file_id); ?>">
            <input type="text" id="kbps_recipe_file_url" name="kbps_recipe_file_url" value="<?php echo esc_attr($current_file); ?>" class="widefat" readonly>
            <button type="button" class="button kbps-upload-file-button" style="margin-top: 10px;">
                <?php _e('Select PDF File', 'kbps'); ?>
            </button>
            <?php if ($current_file): ?>
                <p style="margin-top: 10px;">
                    <a href="<?php echo esc_url($current_file); ?>" target="_blank" class="button button-secondary">
                        <?php _e('View Current File', 'kbps'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    public function save_file_meta($post_id) {
        if (!isset($_POST['kbps_recipe_file_nonce']) || 
            !wp_verify_nonce($_POST['kbps_recipe_file_nonce'], 'kbps_recipe_file')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['kbps_recipe_file_url'])) {
            update_post_meta($post_id, '_recipe_file_url', esc_url_raw($_POST['kbps_recipe_file_url']));
        }
        
        if (isset($_POST['kbps_recipe_file_id'])) {
            update_post_meta($post_id, '_recipe_file_id', absint($_POST['kbps_recipe_file_id']));
        }
        
        // Update associated product downloads if exists
        $product_id = get_post_meta($post_id, '_associated_product_id', true);
        if ($product_id && class_exists('KBPSRecipeProductManager')) {
            $product_manager = new KBPSRecipeProductManager();
            $product_manager->ensure_downloadable_files($product_id);
        }
    }

    public function enqueue_scripts($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php']) || get_current_screen()->post_type !== 'kbps_recipe') {
            return;
        }

        wp_enqueue_media();
        
        wp_enqueue_script(
            'kbps-recipe-admin',
            get_template_directory_uri() . '/assets/js/recipe-admin.js',
            [],
            '1.0.0',
            true
        );
    }
}