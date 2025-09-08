<?php
// WordPress security check
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $uniteGalleryVersion;
$uniteGalleryVersion = "1.7.44";

$currentFile = __FILE__;
$currentFolder = dirname($currentFile);

// Include framework files
require_once $currentFolder . '/inc_php/framework/include_framework.php';

require_once $currentFolder . '/inc_php/unitegallery_globals.class.php';
require_once $currentFolder . '/inc_php/unitegallery_globals_gallery.class.php';
require_once $currentFolder . '/inc_php/unitegallery_operations.class.php';
require_once $currentFolder . '/inc_php/unitegallery_category.class.php';
require_once $currentFolder . '/inc_php/unitegallery_categories.class.php';
require_once $currentFolder . '/inc_php/unitegallery_item.class.php';
require_once $currentFolder . '/inc_php/unitegallery_items.class.php';
require_once $currentFolder . '/inc_php/unitegallery_galleries.class.php';
require_once $currentFolder . '/inc_php/unitegallery_gallery.class.php';
require_once $currentFolder . '/inc_php/unitegallery_gallery_type.class.php';
// Removed duplicate items include
require_once $currentFolder . '/inc_php/unitegallery_helper.class.php';
require_once $currentFolder . '/inc_php/unitegallery_helper_gallery.class.php';

require_once $currentFolder . '/inc_php/unitegallery_manager.class.php';
require_once $currentFolder . '/inc_php/unitegallery_manager_main.class.php';
require_once $currentFolder . '/inc_php/unitegallery_manager_inline.class.php';

// Include all gallery files safely
$objGalleries = new UniteGalleryGalleries();
$arrGalleries = $objGalleries->getArrGalleryTypes();

if (is_array($arrGalleries) && !empty($arrGalleries)) {
    foreach ($arrGalleries as $gallery) {
        if (is_object($gallery) && method_exists($gallery, 'getPathIncludes')) {
            $filepathIncludes = $gallery->getPathIncludes();
            if (!empty($filepathIncludes) && file_exists($filepathIncludes)) {
                require $filepathIncludes;
            }
        }
    }
}
?>
