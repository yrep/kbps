<?php
class KBPSAdmin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menus'));
        add_action('admin_init', array($this, 'front_page_cakes_setting_registration'));
    }



    public function add_admin_menus() {
        add_action('admin_menu', function() {
            add_menu_page(
                __('Krakhmalnikov', 'kbps'),
                __('Krakhmalnikov', 'kbps'),
                'manage_options',
                'kbps_menu',
                '',
                'dashicons-store',
                20
            );
        
            // Remove action of main menu item
            /*
            add_submenu_page(
                'kbps_menu', // Parent menu
                __('KBPS', 'kbps'), // Title
                __('Overview', 'kbps'),
                'manage_options',
                'kbps_menu', // same slug as parent slug
                null // handler
            );
            */
            remove_submenu_page('kbps_menu', 'kbps_menu');
        
        }, 999);


        // Admin settings menu item
        add_action('admin_menu', function() {
            add_submenu_page(
                'kbps_menu',
                'Front Page Settings',
                'Front Page',
                'manage_options',
                'kbps_front_page',
                'kbps_front_page_settings'
            );
        });
    }






    // Settings page
    function kbps_front_page_settings() {
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

    // Setting reg
    function front_page_cakes_setting_registration() {
        register_setting('kbps_front_page_options', 'kbps_front_cake_models');
    }

}



