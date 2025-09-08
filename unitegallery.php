<?php
/*
 Plugin Name: Unite Gallery
 Plugin URI: http://wp.unitegallery.net
 Description: Unite Gallery - All in one image and video gallery
 Author: Valiano
 Version: 1.7.44
 Author URI: http://unitegallery.net
 */

// Enable error reporting for debugging (optional)
// ini_set("display_errors", "on");
// ini_set("error_reporting", E_ALL);

// WordPress security check
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);

try {
    require_once $currentFolder . '/includes.php';
    require_once $currentFolder . "/inc_php/framework/provider/provider_main_file.php";
} catch (Exception $e) {
    // Log error instead of echoing
    error_log("Unite Gallery Error: " . $e->getMessage());
    // Optional: show admin notice
    if (is_admin()) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="notice notice-error"><p>Unite Gallery Error: ' . esc_html($e->getMessage()) . '</p></div>';
        });
    }
}
?>
