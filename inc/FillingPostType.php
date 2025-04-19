<?php
class FillingPostType {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('admin_menu', array($this, 'add_submenu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_filling', array($this, 'save_meta'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('Fillings', 'kbps'),
            'singular_name'      => __('Filling', 'kbps'),
            'menu_name'          => __('Fillings', 'kbps'),
            'name_admin_bar'     => __('Filling', 'kbps'),
            'add_new'            => __('Add New', 'kbps'),
            'add_new_item'       => __('Add New Filling', 'kbps'),
            'edit_item'          => __('Edit Filling', 'kbps'),
            'new_item'           => __('New Filling', 'kbps'),
            'view_item'          => __('View Filling', 'kbps'),
            'all_items'          => __('All Fillings', 'kbps'),
            'search_items'       => __('Search Fillings', 'kbps'),
            'not_found'          => __('No Fillings found', 'kbps'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'show_ui'            => true,
            'show_in_menu'       => false, // Отключаем отдельный пункт меню
            'query_var'          => true,
            'capability_type'    => 'post',
            'supports'           => array('title', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
        );

        register_post_type('filling', $args);
    }

    public function add_submenu() {
        add_submenu_page(
            'kbps_menu', // Родительское меню KBPS
            __('Fillings', 'kbps'),
            __('Fillings', 'kbps'),
            'manage_options',
            'edit.php?post_type=filling'
        );
    }

    public function add_meta_boxes() {
        add_meta_box(
            'kbps_filling_details',
            __('Filling Details', 'kbps'),
            array($this, 'render_meta_box'),
            'filling',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('kbps_filling_meta_box', 'kbps_filling_nonce');

        $description = get_post_meta($post->ID, 'kbps_description', true);

        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="kbps_description">' . __('Description', 'kbps') . '</label></th>';
        echo '<td>';
        echo '<textarea id="kbps_description" name="kbps_description" class="large-text" rows="4">' . esc_textarea($description) . '</textarea>';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label>' . __('Main Image', 'kbps') . '</label></th>';
        echo '<td>' . __('Set using Featured Image', 'kbps') . '</td>';
        echo '</tr>';
        echo '</table>';
    }

    public function save_meta($post_id) {
        if (!isset($_POST['kbps_filling_nonce']) || !wp_verify_nonce($_POST['kbps_filling_nonce'], 'kbps_filling_meta_box')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['kbps_description'])) {
            update_post_meta($post_id, 'kbps_description', sanitize_textarea_field($_POST['kbps_description']));
        }
    }
}
