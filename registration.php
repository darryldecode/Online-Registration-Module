<?php

/**
 * Plugin Name: Online Registration Module
 * Plugin URI: https://github.com/darryldecode
 * Description: Online registration with payment via paypal, lets you deploy form and listings in front end. Manage all beatifully on backend.
 * Version: 1.0
 * Author: Darryl Fernandez | Darrylcoder
 * Author URI: https://github.com/darryldecode
 * License: GPLv2
 * Compatibility: Up to WordPress Version 3.6.1
 *
 */

$eleBootstrap = new EleBootstrap();
$eleBootstrap->init();

/**
 * Online Registration Module Core Bootstrap Class
 *
 * @author: Darryl Fernandez
 */
class EleBootstrap {

    public function init(){
        self::defineConstants();
        self::adminRequiredFiles();
        self::sessionInit();
        register_activation_hook(__FILE__, array($this, 'install'));
        register_deactivation_hook(__FILE__, array($this, 'unInstall'));
        add_action('admin_menu', array($this, 'displayAdminMenu'));
        add_action('admin_menu', array($this, 'displayAdminSubMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
        add_action('admin_enqueue_scripts', array($this, 'printAdminScripts'));
        add_action('wp_enqueue_scripts', array($this, 'loadFrontScripts'));
        add_action('wp_head', array($this, 'printFrontScripts'));
    }

    /**
     * Define all application constants
     * @since 1.0
     */
    public static function defineConstants(){
        define('ELE_PATH',	    dirname(__FILE__) . '/' );
        define('ELE_URI',	    plugins_url().'/registration-module/');
        define('ELE_URI_CSS',	plugins_url().'/registration-module/resources/css/');
        define('ELE_URI_JS',	plugins_url().'/registration-module/resources/js/');
        define('ELE_URI_IMG',	plugins_url().'/registration-module/resources/img/');
    }

    /**
     * Require all needed files
     * @since 1.0
     */
    public static function adminRequiredFiles(){
        require_once 'classes/database.class.php';
        require_once 'classes/install.class.php';
        require_once '_view/admin/admin-view.php';
        require_once '_view/admin/admin-settings-view.php';
        require_once '_view/front/registration-form.php';
        require_once '_view/front/listing.php';
        require_once '_controller/ajax.php';
        require_once '_model/model.php';
    }

    /**
     * Run session if hasn't run yet, this
     * will be use for captcha lib
     * @since 1.0
     */
    public static function sessionInit(){
        if( !session_id())
            session_start();
    }

    /**
     * Installs ele schema and  needed options
     * @since 1.0
     */
    public function install(){
        $eleSet['site_url']  = get_site_url();
        $eleSet['ELE_URI']   = ELE_URI;
        EleInstall::createSchema();
        EleInstall::setOptions( $eleSet );
    }

    /**
     * Proccess uninstall
     * @since 1.0
     */
    public function unInstall(){
        $safe_mode  = unserialize( get_option('ele_settings') );
        $safe_mode  = $safe_mode['ele_safe_mode'];

        if( $safe_mode == 'disabled' ){

            global $wpdb;
            $tbl2 	= 'wp_ele_registrants';
            $tbl1 	= 'wp_ele_entry';

            $wpdb->query("DROP TABLE IF EXISTS $tbl2");
            $wpdb->query("DROP TABLE IF EXISTS $tbl1");

            delete_option('paypal_settings');
            delete_option('athletic_level');
            delete_option('team_category');
            delete_option('ele_settings');
        }
    }

    /**
     * Displays the admin main menu
     * @since 1.0
     */
    public function displayAdminMenu(){
        $page_title		= 'Event Registrants';
        $menu_title		= 'Online Registration';
        $capability		= 'activate_plugins';
        $menu_slug		= 'online-registration';
        $function		= 'ele_online_registration';
        $icon_url		= ELE_URI_IMG.'ele-icon.png';
        $position		= 4;

        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    }

    /**
     * Display the admin panel sub menu
     * @since 1.0
     */
    public function displayAdminSubMenu(){
        $page_title		= 'Online Registration Settings';
        $menu_title		= 'Settings';
        $capability		= 'activate_plugins';
        $parent_slug    = 'online-registration';
        $menu_slug		= 'online-registration-settings';
        $function		= 'ele_online_registration_settings';

        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    }

    /**
     * Load scripts only in ele admin area
     * @since 1.0
     */
    public static function loadAdminScripts(){
        wp_register_style( 'ele_bootstrap_css', ELE_URI.'resources/css/bootstrap.min.css', false, '1.0.0' );
        wp_register_style( 'ele_jqueryui_css', ELE_URI.'resources/css/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, '1.0.0' );
        wp_register_style( 'ele_main_css', ELE_URI.'resources/css/style.css', false, '1.0.0' );
        wp_register_script( 'ele_jquery', ELE_URI.'resources/js/jquery.min.js', false, '1.0.0' );
        wp_register_script( 'ele_jqueryui', ELE_URI.'resources/js/jquery-ui-1.10.3.custom.min.js', false, '1.0.0' );
        wp_register_script( 'ele_angular', ELE_URI.'resources/js/angular.min.js', false, '1.0.0' );
        wp_register_script( 'ele_app', ELE_URI.'app/app.js');

        if( isset($_GET['page']) )
        {
            $page_now = strip_tags($_GET['page']);

            if( $page_now == 'online-registration' || $page_now == 'online-registration-settings' )
            {
                wp_enqueue_style( 'ele_bootstrap_css' );
                wp_enqueue_style( 'ele_jqueryui_css' );
                wp_enqueue_style( 'ele_main_css' );
                wp_enqueue_script( 'ele_jquery' );
                wp_enqueue_script( 'ele_jqueryui' );
                wp_enqueue_script( 'ele_angular' );
                wp_enqueue_script( 'ele_app' );
            }

        }
    }

    /**
     * load scripts on front end
     * @since 1.0
     */
    public static function loadFrontScripts(){
        //wp_register_script( 'ele_jquery_front', ELE_URI_JS.'jquery.min.js', array( 'jquery' ), '1.0.0', true );
        wp_register_script( 'ele_jqueryui_front', ELE_URI_JS.'jquery-ui-1.10.3.custom.min.js', array( 'jquery' ), '1.0.0', true );
        wp_register_script( 'ele_angular_front', ELE_URI_JS.'angular.min.js', array( 'jquery' ), '1.0.0', true );

        wp_register_script( 'ele_app', ELE_URI.'app/app.js', array( 'jquery' ), '1.0.0', true );
        wp_register_script( 'ele_app_front', ELE_URI.'app/app-front.js', array( 'jquery' ), '1.0.0', true );
        wp_register_style( 'ele_front_css', ELE_URI_CSS.'front-end.css', true, '1.0.0' );

        //wp_enqueue_script( 'ele_jquery_front' );
        wp_enqueue_script( 'ele_jqueryui_front' );
        wp_enqueue_script( 'ele_angular_front' );

        wp_enqueue_script( 'ele_app' );
        wp_enqueue_script( 'ele_app_front' );
        wp_enqueue_style( 'ele_front_css' );
    }

    /**
     * Print relevant scripts on header ( admin )
     * @since 1.0
     */
    public static function printAdminScripts(){
        ?>
        <script type="text/javascript">
            var ajaxURL = "<?php echo admin_url('admin-ajax.php'); ?>";
            var eleHomeURL = "<?php echo admin_url('admin.php?page=online-registration#/home/'); ?>";
            var eleTemplateURL = "<?php echo ELE_URI.'view/templates/'; ?>";
        </script>
        <?php
    }

    /**
     * Print relevant scripts on header ( front end )
     * Mostly use for ajax transactions
     * @since 1.0
     */
    public static function printFrontScripts(){
        ?>
        <script type="text/javascript">
            var ajaxURL = "<?php echo admin_url('admin-ajax.php'); ?>";
        </script>
        <?php
    }


}

