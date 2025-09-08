<?php
/**
 * @package Unite Gallery
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UniteGalleryAdmin extends UniteBaseAdminClassUG {

    const DEFAULT_VIEW = "galleries";

    public static $currentGalleryType;

    /**
     * The constructor
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Init the gallery framework by type name
     */
    protected static function initGalleryFramework($galleryTypeName, $galleryID = "") {

        $objGallery = null;
        if (!empty($galleryID)) {
            $objGallery = new UniteGalleryGallery();
            $objGallery->initByID($galleryID);
            $galleryTypeName = $objGallery->getTypeName();
        }

        UniteFunctionsUG::validateNotEmpty($galleryTypeName, "Gallery Type Name");

        $galleries = new UniteGalleryGalleries();

        self::$currentGalleryType = new UniteGalleryGalleryType();
        self::$currentGalleryType = $galleries->getGalleryTypeByName($galleryTypeName);

        GlobalsUGGallery::init(self::$currentGalleryType, $objGallery, $galleryID);
    }

    /**
     * Init current gallery for gallery view only
     */
    protected function initCurrentGallery() {

        switch (self::$view) {
            case GlobalsUG::VIEW_GALLERY:
            case GlobalsUG::VIEW_PREVIEW:
            case GlobalsUG::VIEW_CATEGORY_TABS:
            case GlobalsUG::VIEW_ADVANCED:
                $galleryID = UniteFunctionsUG::getPostGetVariable("id", "", UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
                break;
            case GlobalsUG::VIEW_ITEMS:
                $galleryID = UniteFunctionsUG::getPostGetVariable("galleryid", "", UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
                if (empty($galleryID)) return false;
                break;
            default:
                return false;
        }

        $objGallery = null;
        if (!empty($galleryID)) {
            $objGallery = new UniteGalleryGallery();
            $objGallery->initByID($galleryID);
            $galleryTypeName = $objGallery->getTypeName();
        } else {
            $galleryTypeName = UniteFunctionsUG::getPostGetVariable("type");
        }

        self::initGalleryFramework($galleryTypeName, $galleryID);
    }

    /**
     * Validate current gallery initialized
     */
    protected static function validateCurrentGalleryInited() {
        if (empty(self::$currentGalleryType))
            UniteFunctionsUG::throwError("Current gallery is not initialized");
    }

    /**
     * Init all actions
     */
    public function init() {
        GlobalsUG::$is_admin = true;
        $this->initCurrentGallery();
    }

    /**
     * Add provider scripts
     */
    public static function addProviderScripts() {
        HelperUG::addStyleAbsoluteUrl(GlobalsUG::$url_provider . "assets/provider_admin.css", "provider_admin_css");
        HelperUG::addScriptAbsoluteUrl(GlobalsUG::$url_provider . "assets/provider_admin.js", "provider_admin_js");
    }

    /**
     * Add scripts to normal pages
     */
    public static function addScriptsNormal() {
        parent::addCommonScripts();

        HelperUG::addScript("unitegallery_admin");
        UniteGalleryManager::putScriptsIncludes(UniteGalleryManager::TYPE_MAIN);
        HelperUG::addStyle("unitegallery_styles", "unitegallery_css", "css");

        if (!empty(self::$currentGalleryType)) {
            $pathGalleryScripts = self::$currentGalleryType->getPathScriptsIncludes();
            if (file_exists($pathGalleryScripts)) require $pathGalleryScripts;
        }

        // provider admin always comes at end
        self::addProviderScripts();
    }

    /**
     * Add manager-only scripts for outside pages
     */
    public static function addScriptsManagerOnly() {
        parent::addCommonScripts();
        UniteGalleryManager::putScriptsIncludes(UniteGalleryManager::TYPE_MAIN);

        // provider admin always comes at end
        self::addProviderScripts();
    }

    /**
     * Must function. Adds scripts on page
     */
    public static function onAddScripts() {
        if (self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
            self::addScriptsNormal();
    }

    /**
     * Admin main page function
     */
    public static function adminPages() {
        if (self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
            self::setMasterView("master_view");

        self::requireView(self::$view);
    }

    /**
     * Call gallery action, include gallery framework first
     */
    public static function onGalleryAjaxAction($typeName, $action, $data, $galleryID) {
        if (empty($data)) $data = array();

        self::initGalleryFramework($typeName, $galleryID);

        $filepathAjax = GlobalsUGGallery::$pathBase . "ajax_actions.php";
        UniteFunctionsUG::validateFilepath($filepathAjax, "Ajax request error: ");

        require $filepathAjax;

        UniteFunctionsUG::throwError("No ajax response from gallery: <b>{$typeName}</b> to action <b>{$action}</b>");
    }

    /**
     * On Ajax action handler
     */
    public static function onAjaxAction() {

        $actionType = UniteFunctionsUG::getPostGetVariable("action");

        if ($actionType != "unitegallery_ajax_action") return false;

        $gallery = new UniteGalleryGallery();
        $galleries = new UniteGalleryGalleries();
        $categories = new UniteGalleryCategories();
        $items = new UniteGalleryItems();
        $operations = new UGOperations();

        $action = UniteFunctionsUG::getPostGetVariable("client_action");

        $data = UniteFunctionsUG::getPostVariable("data");
        if (empty($data)) $data = $_REQUEST;

        if (is_string($data)) {
            $arrData = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($arrData)) {
                $arrData = json_decode(stripslashes(trim($data)), true);
            }
            $data = is_array($arrData) ? $arrData : array();
        }

        $data = UniteProviderFunctionsUG::normalizeAjaxInputData($data);
        $galleryType = UniteFunctionsUG::getPostVariable("gallery_type");
        $urlGalleriesView = HelperUG::getGalleriesView();

        try {

            switch ($action) {
                // ... keep all the existing case actions as-is ...
                default:
                    HelperUG::ajaxResponseError("Wrong ajax action: <b>$action</b>");
                    break;
            }

        } catch (Exception $e) {
            $message = $e->getMessage();
            $errorMessage = $message;
            if (GlobalsUG::SHOW_TRACE) {
                $trace = $e->getTraceAsString();
                $errorMessage = $message . "<pre>" . $trace . "</pre>";
            }
            HelperUG::ajaxResponseError($errorMessage);
        }

        // Exit because it’s an ajax action
        HelperUG::ajaxResponseError("No response output on <b>$action</b> action. Please check with the developer.");
        exit();
    }

}
?>
