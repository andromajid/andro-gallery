<?php

/*
  Plugin Name: andro gallery
  Plugin URI: http://andromajid.com
  Description: wordpress plugin buat nampilin gallery
  Author: andro majid
  Version: 0.1
  Author URI: http://andromajid.com
 */


/* ==================================================================================
 * Create custom database table
 * ================================================================================== 
 */

global $andro_gallery_table;
global $andro_gallery_image_table;
global $andro_gallery_db_version;
global $wpdb;
$andro_gallery_table = $wpdb->prefix . 'andro_gallery'; //image category
$andro_gallery_image_table = $wpdb->prefix . 'andro_gallery_images'; //images gallery
$andro_gallery_db_version = '1.1';
//die($andro_gallery_table);
register_activation_hook(__FILE__, 'easy_gallery_install');

function easy_gallery_install() {
    global $wpdb;
    global $andro_gallery_table;
    global $andro_gallery_image_table;
    global $andro_gallery_db_version;
    //cek table ada tidak yang seperti database tersebut
    if ($wpdb->get_var("show tables like '" . $andro_gallery_table . "'") != $andro_gallery_table) {

        $sql = "CREATE TABLE $andro_gallery_table (" .
                "gallery_id INT NOT NULL AUTO_INCREMENT, " .
                "gallery_name VARCHAR( 30 ) NOT NULL, " .
                "gallery_slug VARCHAR( 30 ) NOT NULL, " .
                "description TEXT NOT NULL, " .
                "PRIMARY KEY gallery_id (gallery_id) " .
                ")";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        $sql_image = "CREATE TABLE $andro_gallery_image_table (" .
                "gallery_image_id INT NOT NULL AUTO_INCREMENT, " .
                "gallery_image_gallery_id INT NOT NULL, " .
                "gallery_image_path LONGTEXT NOT NULL, " .
                "gallery_image_title VARCHAR( 50 ) NOT NULL, " .
                "gallery_image_description LONGTEXT NOT NULL, " .
                "gallery_image_sort_order INT NOT NULL, " .
                "PRIMARY KEY gallery_image_id (gallery_image_id) " .
                ")";
        dbDelta($sql_image);

        add_option("easy_gallery_db_version", $andro_gallery_db_version);
    }
}

/* ==================================================================================
 * Include JS File in Header
 * ================================================================================== 
 */

function attach_EasyGallery_jquery() {
    wp_enqueue_script('jquery');
}

//add javascript jquery
add_action('wp_enqueue_scripts', 'attach_EasyGallery_jquery');

function attach_Easy_Gallery_JS() {
    if (!defined('ANDROGALLERY_PLUGIN_BASENAME'))
        define('ANDROGALLERY_PLUGIN_BASENAME', plugin_basename(__FILE__));

    if (!defined('ANDROGALLERY_PLUGIN_NAME'))
        define('ANDROGALLERY_PLUGIN_NAME', trim(dirname(ANDROGALLERY_PLUGIN_BASENAME), '/'));

    if (!defined('ANDROGALLERY_PLUGIN_DIR'))
        define('ANDROGALLERY_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . ANDROGALLERY_PLUGIN_NAME);

    $scripts = "<link rel=\"stylesheet\" href=\"" . get_settings('home') . "/wp-content/plugins/" . ANDROGALLERY_PLUGIN_NAME . "/css/andro_slideshow.css\" type=\"text/css\" media=\"screen\" title=\"Andro Gallery main stylesheet\" charset=\"utf-8\" />\n";
    $scripts = $scripts . "<link rel=\"stylesheet\" href=\"" . get_settings('home') . "/wp-content/plugins/" . ANDROGALLERY_PLUGIN_NAME . "/css/colorbox.css\" type=\"text/css\" />\n";
    $scripts = $scripts . "<script type=\"text/javascript\" src=\"" . get_settings('home') . "/wp-content/plugins/" . ANDROGALLERY_PLUGIN_NAME . "/js/colorbox.js\"></script>\n";
    $scripts .= "<script>jQuery(document).ready(function() { jQuery('.andro_container .andro-gallery').colorbox({rel:'andro-gallery', transition:\"fade\"});});</script>";
    echo $scripts;
}

add_action('wp_head', 'attach_Easy_Gallery_JS');

function andro_gallery_admin_scripts() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('andro-gallery-uploader', WP_PLUGIN_URL . '/andro-gallery/js/image-uploader.js', array('jquery', 'media-upload', 'thickbox'));
    wp_enqueue_script('andro-gallery-uploader');
}

function andro_gallery_admin_styles() {
    wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && ($_GET['page'] == 'add-gallery' || $_GET['page'] == 'add-images' || $_GET['page'] == 'edit-gallery')) {
    add_action('admin_print_scripts', 'andro_gallery_admin_scripts');
    add_action('admin_print_styles', 'andro_gallery_admin_styles');
}

// Create Admin Panel
function add_admin_menu() {
    add_menu_page(__('Andro Gallery'), __('Andro Gallery'), 'manage_options', 'andro-admin', 'show_andro_menu');

    // Add a submenu to the custom top-level menu:
    add_submenu_page('andro-admin', __('Andro Gallery >> Add Gallery'), __('Add Gallery'), 'manage_options', 'add-gallery', 'add_gallery');

    // Add a submenu to the custom top-level menu:
    add_submenu_page('andro-admin', __('Andro Gallery >> Edit Gallery'), __('Edit Gallery'), 'manage_options', 'edit-gallery', 'edit_gallery');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('andro-admin', __('Andro Gallery >> Add Images'), __('Add Images'), 'manage_options', 'add-images', 'add_images');
}

add_action('admin_menu', 'add_admin_menu');

function show_andro_menu() {
    include("admin/overview.php");
}

function add_gallery() {
    include("admin/add-gallery.php");
}

function edit_gallery() {
    include("admin/edit-gallery.php");
}

function add_images() {
    include("admin/add-images.php");
}

/* ==================================================================================
 * Gallery Creation Filter
 * ================================================================================== 
 */

// function creates the gallery
function create_image_gallery($gallery_name) {
    global $wpdb;
    global $andro_gallery_table;
    global $andro_gallery_image_table;

    $gallery = $wpdb->get_row("SELECT gallery_id,gallery_name FROM $andro_gallery_table WHERE gallery_slug = '$gallery_name'");
    $imageResults = $wpdb->get_results("SELECT * FROM $andro_gallery_image_table WHERE gallery_image_gallery_id = $gallery->gallery_id ORDER BY gallery_image_sort_order ASC");

    $images = '';
    $i = 0;
    foreach ($imageResults as $image) {
        $images .= '<img href="'.$image->gallery_image_path.'" class="andro-gallery" height="100" width="100" src="'.$image->gallery_image_path.'" title="'.$image->gallery_image_description.'"/>';
    }
    return generate_html($images);
}

function andro_gallery_handler($atts) {
    return create_image_gallery($atts['id']);
}

add_shortcode('andro_gallery', 'andro_gallery_handler');
/**
 * Function to make container for thumbnail image
 * @param String $img img source
 * @return String $html generated html
 */
function generate_html($img) {
    $html = '<div class="andro_container">';
    $html .= $img;
    $html .= "</div>";
    return $html;
}
?>