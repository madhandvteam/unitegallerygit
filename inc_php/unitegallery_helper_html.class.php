<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


/**
 * 
 * gallery helper functions class
 *
 */
class HelperHTMLUG{
			
	/**
	 *
	 * get link html
	 */
	public static function getHtmlLink($link,$text,$id="",$class="", $isNewWindow = false){
	
		if(!empty($class))
			$class = " class='$class'";
	
		if(!empty($id))
			$id = " id='$id'";
	
		$htmlAdd = "";
		if($isNewWindow == true)
			$htmlAdd = ' target="_blank"';
	
		$html = "<a href=\"$link\"".$id.$class.$htmlAdd.">$text</a>";
		return($html);
	}

	
	/**
	 *
	 * get select from array
	 */
	public static function getHTMLSelect($arr,$default="",$htmlParams="",$assoc = false, $addData = null, $addDataText = null){
	
		$html = "<select $htmlParams>";
		//add first item
		if($addData == "not_chosen"){
			$selected = "";
			$default = trim($default);
			if(empty($default))
				$selected = " selected='selected' ";
				
			$itemText = $addDataText;
			if(empty($itemText))
				$itemText = "[".esc_html__("not chosen", "unlimited-elements-for-elementor")."]";
				
			$html .= "<option $selected value=''>{$itemText}</option>";
		}
		
		foreach($arr as $key=>$item){
			$selected = "";
	
			if($assoc == false){
				if($item == $default) 
					$selected = " selected='selected' ";
			}
			else{
				if(trim($key) == trim($default))
					$selected = " selected='selected' ";
			}
			
			$addHtml = "";
			if(strpos($key, "html_select_sap") !== false)
				$addHtml = " disabled";
			
			if($assoc == true)
				$html .= "<option $selected value='$key' $addHtml>$item</option>";
			else
				$html .= "<option $selected value='$item' $addHtml>$item</option>";
		}
		$html.= "</select>";
		return($html);
	}
	
	
}
	