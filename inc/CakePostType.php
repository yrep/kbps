<?php
class CakePostType {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
        add_action('admin_menu', array($this, 'add_submenu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_cake', array($this, 'save_meta'));
        add_action('init', array($this, 'add_default_term'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('Cakes', 'kbps'),
            'singular_name'      => __('Cake', 'kbps'),
            'menu_name'          => __('Cakes', 'kbps'),
            'name_admin_bar'     => __('Cake', 'kbps'),
            'add_new'            => __('Add New', 'kbps'),
            'add_new_item'       => __('Add New Cake', 'kbps'),
            'edit_item'          => __('Edit Cake', 'kbps'),
            'new_item'           => __('New Cake', 'kbps'),
            'view_item'          => __('View Cake', 'kbps'),
            'all_items'          => __('All Cakes', 'kbps'),
            'search_items'       => __('Search Cakes', 'kbps'),
            'not_found'          => __('No Cakes found', 'kbps'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'capability_type'    => 'post',
            'rewrite'            => ['slug' => 'cake'],
            'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
            //'supports'           => array('title', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
            'taxonomies'         => array('cake_type'),
        );

        register_post_type('cake', $args);
    }

    public function register_taxonomy() {
        $labels = array(
            'name'              => __('Cake Types', 'kbps'),
            'singular_name'     => __('Cake Type', 'kbps'),
            'search_items'      => __('Search Cake Types', 'kbps'),
            'all_items'         => __('All Cake Types', 'kbps'),
            'edit_item'         => __('Edit Cake Type', 'kbps'),
            'update_item'       => __('Update Cake Type', 'kbps'),
            'add_new_item'      => __('Add New Cake Type', 'kbps'),
            'new_item_name'     => __('New Cake Type Name', 'kbps'),
            'menu_name'         => __('Cake Types', 'kbps'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
        );

        register_taxonomy('cake_type', array('cake'), $args);
    }

    public function add_default_term() {
        if (!term_exists('Svatební dort', 'cake_type')) {
            wp_insert_term('Svatební dort', 'cake_type');
        }
    }

    public function add_submenu() {
        add_submenu_page(
            'kbps_menu',
            __('Cakes', 'kbps'),
            __('Cakes', 'kbps'),
            'manage_options',
            'edit.php?post_type=cake'
        );
    }

    public function enqueue_scripts($hook) {
        if (in_array($hook, array('post.php', 'post-new.php')) && get_current_screen()->post_type === 'cake') {
            wp_enqueue_media();
            wp_enqueue_script(
                'kbps-cake-gallery',
                get_template_directory_uri() . '/assets/js/cake-gallery.js',
                array(),
                '1.0.1',
                true
            );
        }
    }

    public function add_meta_boxes() {
        add_meta_box(
            'kbps_cake_details',
            __('Cake Details', 'kbps'),
            array($this, 'render_meta_box'),
            'cake',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('kbps_cake_meta_box', 'kbps_cake_nonce');

        $fields = array(
            'kbps_model'       => __('Model Number', 'kbps'),
            'kbps_description' => __('Description', 'kbps'),
            'kbps_ingredients' => __('Ingredients (comma-separated)', 'kbps'),
            'kbps_finishing'   => __('Finishing', 'kbps'),
            'kbps_decoration'  => __('Decoration', 'kbps'),
            'kbps_tiers'       => __('Tiers', 'kbps'),
            'kbps_shape'       => __('Shape', 'kbps'),
        );

        echo '<table class="form-table">';
        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, $key, true);
            echo '<tr>';
            echo '<th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
            echo '<td>';
            if ($key === 'kbps_description' || $key === 'kbps_ingredients') {
                echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" class="large-text" rows="4">' . esc_textarea($value) . '</textarea>';
            } elseif ($key === 'kbps_tiers') {
                echo '<input type="number" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" min="1">';
            } else {
                echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';
            }
            echo '</td>';
            echo '</tr>';
        }

        // Available Fillings
        $filling_ids = get_post_meta($post->ID, 'kbps_available_fillings', true);
        $filling_ids = $filling_ids ? explode(',', $filling_ids) : array();
        $fillings = get_posts(array(
            'post_type' => 'filling',
            'numberposts' => -1,
            'post_status' => 'publish',
        ));

        echo '<tr>';
        echo '<th><label for="kbps_available_fillings">' . __('Available Fillings', 'kbps') . '</label></th>';
        echo '<td>';
        echo '<select id="kbps_available_fillings" name="kbps_available_fillings[]" multiple class="widefat" style="height: 100px;">';
        foreach ($fillings as $filling) {
            echo '<option value="' . esc_attr($filling->ID) . '" ' . (in_array($filling->ID, $filling_ids) ? 'selected' : '') . '>' . esc_html($filling->post_title) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Hold Ctrl/Cmd to select multiple fillings.', 'kbps') . '</p>';
        echo '</td>';
        echo '</tr>';

        // Main Image
        echo '<tr>';
        echo '<th><label>' . __('Main Image', 'kbps') . '</label></th>';
        echo '<td>' . __('Set using Featured Image', 'kbps') . '</td>';
        echo '</tr>';

        // Gallery
        $gallery = get_post_meta($post->ID, 'kbps_gallery', true);
        echo '<tr>';
        echo '<th><label>' . __('Gallery', 'kbps') . '</label></th>';
        echo '<td>';
        echo '<input type="hidden" id="kbps_gallery" name="kbps_gallery" value="' . esc_attr($gallery) . '">';
        echo '<button type="button" class="button kbps-gallery-upload">' . __('Select Images', 'kbps') . '</button>';
        echo '<div id="kbps-gallery-preview" style="margin-top: 10px;">';
        if ($gallery) {
            $image_ids = explode(',', $gallery);
            foreach ($image_ids as $image_id) {
                $image = wp_get_attachment_image($image_id, 'thumbnail');
                if ($image) {
                    echo '<div class="kbps-gallery-image" style="display: inline-block; margin: 5px; position: relative;">';
                    echo $image;
                    echo '<span class="kbps-gallery-remove" data-id="' . esc_attr($image_id) . '" style="position: absolute; top: 0; right: 0; cursor: pointer; background: red; color: white; padding: 2px 5px;">×</span>';
                    echo '</div>';
                }
            }
        }
        echo '</div>';
        echo '<p class="description">' . __('Select multiple images for the gallery.', 'kbps') . '</p>';
        echo '</td>';
        echo '</tr>';

        echo '</table>';
    }

    public function save_meta($post_id) {
        if (!isset($_POST['kbps_cake_nonce']) || !wp_verify_nonce($_POST['kbps_cake_nonce'], 'kbps_cake_meta_box')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $fields = array(
            'kbps_model'       => 'sanitize_text_field',
            'kbps_description' => 'sanitize_textarea_field',
            'kbps_ingredients' => 'sanitize_textarea_field',
            'kbps_finishing'   => 'sanitize_text_field',
            'kbps_decoration'  => 'sanitize_text_field',
            'kbps_tiers'       => 'absint',
            'kbps_shape'       => 'sanitize_text_field',
            'kbps_gallery'     => 'sanitize_text_field',
        );

        foreach ($fields as $field => $sanitize) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
            }
        }

        // Save available fillings
        if (isset($_POST['kbps_available_fillings']) && is_array($_POST['kbps_available_fillings'])) {
            $filling_ids = array_map('intval', $_POST['kbps_available_fillings']);
            update_post_meta($post_id, 'kbps_available_fillings', implode(',', $filling_ids));
        } else {
            update_post_meta($post_id, 'kbps_available_fillings', '');
        }
    }
}
