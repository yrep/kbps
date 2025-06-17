<?php
class KBPSCakeRequestPostType {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_cake_request', array($this, 'save_meta_box_data'));
        add_filter('wp_insert_post_data', array($this, 'set_default_post_status'), 10, 2);
    }

    public function register_post_type() {
        $labels = array(
            'name'               => __('Cake Requests', 'kbps'),
            'singular_name'      => __('Cake Request', 'kbps'),
            'menu_name'          => __('Cake Requests', 'kbps'),
            'name_admin_bar'     => __('Cake Request', 'kbps'),
            'add_new'            => __('Add New', 'kbps'),
            'add_new_item'       => __('Add New Cake Request', 'kbps'),
            'edit_item'          => __('Edit Cake Request', 'kbps'),
            'new_item'           => __('New Cake Request', 'kbps'),
            'view_item'          => __('View Cake Request', 'kbps'),
            'all_items'          => __('All Cake Requests', 'kbps'),
            'search_items'       => __('Search Cake Requests', 'kbps'),
            'not_found'          => __('No Cake Requests found', 'kbps'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => false,
            'capability_type'    => 'post',
            'capabilities'       => array(
                'edit_post'          => 'manage_options',
                'read_post'          => 'manage_options',
                'delete_post'        => 'manage_options',
                'edit_posts'         => 'manage_options',
                'edit_others_posts'  => 'manage_options',
                'publish_posts'      => 'manage_options',
                'read_private_posts' => 'manage_options',
                'create_posts'       => 'manage_options',
            ),
            'supports'           => array('title'),
            'show_in_rest'       => false,
            'exclude_from_search' => true,
            'show_in_nav_menus'  => false,
        );

        register_post_type('cake_request', $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'kbps_cake_request_details',
            __('Order Details', 'kbps'),
            array($this, 'render_meta_box'),
            'cake_request',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('kbps_cake_request_meta_box', 'kbps_cake_request_nonce');

        $fields = array(
            'kbps_cake_model'    => __('Model dortu', 'kbps'),
            'kbps_name'          => __('Jméno', 'kbps'),
            'kbps_mobile'        => __('Telefonní číslo', 'kbps'),
            'kbps_email'         => __('Email', 'kbps'),
            'kbps_wedding_date'  => __('Datum svatby', 'kbps'),
            'kbps_venue'         => __('Místo svatby', 'kbps'),
            'kbps_guests'        => __('Počet hostů', 'kbps'),
        );

        $status = get_post_meta($post->ID, 'kbps_status', true);
        if (empty($status)) {
            $status = 'new';
        }

        echo '<table class="form-table">';

        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, $key, true);
            echo '<tr>';
            echo '<th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
            echo '<td>';
            if ($key === 'kbps_guests') {
                echo '<input type="number" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text" min="1">';
            } elseif ($key === 'kbps_wedding_date') {
                echo '<input type="date" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';
            } else {
                echo '<input type="text" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="regular-text">';
            }
            echo '</td>';
            echo '</tr>';
        }

        $statuses = array(
            'new'      => __('New', 'kbps'),
            'accepted' => __('Accepted', 'kbps'),
            'canceled' => __('Canceled', 'kbps'),
            'ordered'  => __('Ordered', 'kbps'),
        );
        echo '<tr>';
        echo '<th><label for="kbps_status">' . __('Status', 'kbps') . '</label></th>';
        echo '<td>';
        echo '<select id="kbps_status" name="kbps_status">';
        foreach ($statuses as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($status, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<th><label>' . __('Email Sent', 'kbps') . '</label></th>';
        echo '<td>' . esc_html(get_post_meta($post->ID, 'kbps_email_sent', true) ? __('Yes', 'kbps') : __('No', 'kbps')) . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label>' . __('Error', 'kbps') . '</label></th>';
        echo '<td>' . esc_html(get_post_meta($post->ID, 'kbps_error', true)) . '</td>';
        echo '</tr>';

        echo '</table>';
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['kbps_cake_request_nonce']) || !wp_verify_nonce($_POST['kbps_cake_request_nonce'], 'kbps_cake_request_meta_box')) {
            return;
        }

        if (!current_user_can('manage_options', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $fields = array(
            'kbps_cake_model',
            'kbps_name',
            'kbps_mobile',
            'kbps_email',
            'kbps_wedding_date',
            'kbps_venue',
            'kbps_guests',
            'kbps_status',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'kbps_email') {
                    $value = sanitize_email($_POST[$field]);
                } elseif ($field === 'kbps_guests') {
                    $value = absint($_POST[$field]);
                } elseif ($field === 'kbps_status') {
                    $value = in_array($_POST[$field], array('new', 'accepted', 'canceled', 'ordered')) ? $_POST[$field] : 'new';
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_post_meta($post_id, $field, $value);
            }
        }
    }

    public function set_default_post_status($data, $postarr) {
        if ($postarr['post_type'] === 'cake_request' && $data['post_status'] === 'publish') {
            $data['post_status'] = 'private';
        }
        return $data;
    }
}
