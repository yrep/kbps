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

        add_submenu_page(
            'kbps_menu',
            __('Cake Settings', 'kbps'),
            __('Cake Settings', 'kbps'),
            'manage_options',
            'kbps_cake_settings',
            array($this, 'render_cake_settings_page')
        );

        add_submenu_page(
            'kbps_menu',
            __('Cake Orders', 'kbps'),
            __('Cake Orders', 'kbps'),
            'manage_options',
            'edit.php?post_type=cake_request'
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

    /*
    public function front_page_cakes_setting_registration() {
        register_setting('kbps_front_page_options', 'kbps_front_cake_models');
    }
    */

    //TODO make universal
    public function front_page_cakes_setting_registration() {
        register_setting('kbps_front_page_options', 'kbps_front_cake_models');
        register_setting('kbps_cake_settings_group', 'kbps_cake_default_description');
        register_setting('kbps_cake_settings_group', 'kbps_cake_append_mode'); // checkbox append description

        register_setting('kbps_cake_settings_group', 'kbps_wedding_cakes_archive_title_text');

        add_settings_section(
            'kbps_cake_main_section',
            __('Cake Default Settings', 'kbps'),
            null,
            'kbps_cake_settings'
        );



         // Cake Type Wedding
        add_settings_field(
            'kbps_wedding_cakes_archive_title_text',
            __('Text for Wedding Cakes Archive Page', 'kbps'),
            function () {
                $value = get_option('kbps_wedding_cakes_archive_title_text', '');
                echo '<textarea name="kbps_wedding_cakes_archive_title_text" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
                echo '<p class="description">Zadejte text, který se má zobrazit v levé kolonce na stránce archivu svatebních dortů.</p>';
            },
            'kbps_cake_settings',
            'kbps_cake_main_section'
        );








        add_settings_field(
            'kbps_cake_default_description',
            __('Default Cake Description', 'kbps'),
            function () {
                $value = get_option('kbps_cake_default_description', '');
                echo '<textarea name="kbps_cake_default_description" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
            },
            'kbps_cake_settings',
            'kbps_cake_main_section'
        );

        add_settings_field(
            'kbps_cake_append_mode',
            __('Append to Custom Description?', 'kbps'),
            function () {
                $checked = get_option('kbps_cake_append_mode') ? 'checked' : '';
                echo '<input type="checkbox" name="kbps_cake_append_mode" value="1" ' . $checked . '> ' . __('Yes, append to custom description', 'kbps');
            },
            'kbps_cake_settings',
            'kbps_cake_main_section'
        );
    }

    public function render_cake_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Cake Settings', 'kbps'); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('kbps_cake_settings_group');
                    do_settings_sections('kbps_cake_settings');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }


}