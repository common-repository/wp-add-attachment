<?php
	/*Plugin Name: Bulk add files to media
      Author:  Mobin Ghasempoor
      Version:1.0.0
      Description: By this plugin you can add all files from a folder to your media. But upload has two condition, files are't duplicates and don't already exists in the media
      Text Domain: attach_media
      Domain Path: /languages/
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    define('attach_dir',dirname(__file__));
    define('attach_admin',attach_dir.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR);
    define('attach_lib',attach_dir.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR);
    include_once(attach_lib.'function.php');
//=========================================================== add plugin menu page ===================================================================    
    add_action('admin_menu','mgh_attach_admin_menu');
    
    function mgh_attach_admin_menu(){
        add_menu_page(__('پلاگین افزدون به مدیا','attach_media'),__('پلاگین افزدون به مدیا','attach_media'),'administrator','mgh_add_attach_config','mgh_add_attach_config');
        add_submenu_page('mgh_add_attach_config',__('انتقال به پوشه مدیا','attach_media'),__('انتقال به پوشه مدیا','attach_media'),'administrator','mgh_add_attach_mediadir','mgh_add_attach_mediadir');
    }
    
    function mgh_add_attach_config(){
        include_once(attach_admin.'config.php');
    }
    
    function mgh_add_attach_mediadir(){
        include_once(attach_admin.'mediadir.php');
    }
//========================================================= plugin ajax dir list ====================================================================

add_action('wp_ajax_wp_attach_getdirlist','mgh_attach_getdirlist');

function mgh_attach_getdirlist(){
    die(attach_dir_manager($_POST['path']));
}
?>