<?php
class KBPSAdmin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menus'), 20);
        add_action('admin_init', array($this, 'front_page_cakes_setting_registration'));
    }

    public function add_admin_menus() {
        add_menu_page(
            __('Krakhmalnikov', 'kbps'),
            __('Krakhmalnikov', 'kbps'),
            'manage_options',
            'kbps_menu',
            '',
            'dashicons-store',
            20
        );

        global $submenu;
        if (isset($submenu['kbps_menu'])) {
            foreach ($submenu['kbps_menu'] as $key => $item) {
                if ($item[2] === 'kbps_menu') {
                    unset($submenu['kbps_menu'][$key]);
                    break;
                }
            }
        }

        add_submenu_page(
            'kbps_menu',
            'Front Page Settings',
            'Front Page',
            'manage_options',
            'kbps_front_page',
            array($this, 'kbps_front_page_settings')
        );

        add_submenu_page(
            'kbps_menu',
            __('Cakes', 'kbps'),
            __('Cakes', 'kbps'),
            'manage_options',
            'edit.php?post_type=cake'
        );

        add_submenu_page(
            'kbps_menu',
            __('Fillings', 'kbps'),
            __('Fillings', 'kbps'),
            'manage_options',
            'edit.php?post_type=filling'
        );

        add_submenu_page(
            'kbps_menu',
            __('Cake Types', 'kbps'),
            __('Cake Types', 'kbps'),
            'manage_options',
            'edit-tags.php?taxonomy=cake_type&post_type=cake'
        );
    }

    public function kbps_front_page_settings() {
        ?>
        <div class="wrap">
            <h1>Front Page Cake Models</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('kbps_front_page_options');
                do_settings_sections('kbps_front_page');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Model Numbers (comma separated)</th>
                        <td>
                            <input type="text" name="kbps_front_cake_models" 
                                value="<?php echo esc_attr(get_option('kbps_front_cake_models')); ?>" 
                                class="regular-text" />
                            <p class="description">Enter model numbers (1,2,3,4) to display on front page</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function front_page_cakes_setting_registration() {
        register_setting('kbps_front_page_options', 'kbps_front_cake_models');
    }
}