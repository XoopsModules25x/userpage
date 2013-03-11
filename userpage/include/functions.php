<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * Returns a module's option
 */
function userpage_getmoduleoption($option, $repmodule='userpage')
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions = array();
	if(is_array($tbloptions) && array_key_exists($option,$tbloptions)) {
		return $tbloptions[$option];
	}

	$retval = false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval = $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval = $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option] = $retval;
	return $retval;
}


/**
 * Suppression du cache des blocs du module
 */
function userpage_updateCache() {
	global $xoopsModule;
	$tpllist = array('userpage_block_last.html','userpage_block_top.html','userpage_block_random.html');
	include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
	include_once XOOPS_ROOT_PATH.'/class/template.php';
	xoops_template_clear_module_cache($xoopsModule->getVar('mid'));		// Clear blocks cache
	$xoopsTpl = new XoopsTpl();		// Clear pages cache
	foreach ($tpllist as $onetemplate) {
		$xoopsTpl->clear_cache('db:'.$onetemplate);
	}
}

/**
 * Internal function used for PDF
 */
function userpage_html2text($document)
{
	// PHP Manual:: function preg_replace
	// $document should contain an HTML document.
	// This will remove HTML tags, javascript sections
	// and white space. It will also convert some
	// common HTML entities to their text equivalent.

	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
	                 "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
	                 "'([\r\n])[\s]+'",                // Strip out white space
	                 "'&(quot|#34);'i",                // Replace HTML entities
	                 "'&(amp|#38);'i",
	                 "'&(lt|#60);'i",
	                 "'&(gt|#62);'i",
	                 "'&(nbsp|#160);'i",
	                 "'&(iexcl|#161);'i",
	                 "'&(cent|#162);'i",
	                 "'&(pound|#163);'i",
	                 "'&(copy|#169);'i",
	                 "'&#(\d+);'e");                    // evaluate as php

	$replace = array ("",
	                 "",
	                 "\\1",
	                 "\"",
	                 "&",
	                 "<",
	                 ">",
	                 " ",
	                 chr(161),
	                 chr(162),
	                 chr(163),
	                 chr(169),
	                 "chr(\\1)");

	$text = preg_replace($search, $replace, $document);
	return $text;
}

/**
 * Meta keywords automatic's creation
 */
function userpage_createmeta_keywords($content)
{
	$tmp = array();
	// Search for the "Minimum keyword length"
	if(isset($_SESSION['userpage_keywords_limit'])) {
		$limit = intval($_SESSION['userpage_keywords_limit']);
	} else {
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
		$limit = $xoopsConfigSearch['keyword_min'];
		$_SESSION['userpage_keywords_limit']=$limit;
	}
	$myts =& MyTextSanitizer::getInstance();
	$content = str_replace ("<br />", " ", $content);
	$content= strip_tags($content);
	$content = $myts->undoHtmlSpecialChars($content);
	$content = strtolower($content);
	$search_pattern = array("&nbsp;","\t","\r\n","\r","\n",",",".","'",";",":",")","(",'"','?','!','{','}','[',']','<','>','/','+','-','_','\\','*');
	$replace_pattern = array(' ',' ',' ',' ',' ',' ',' ',' ','','','','','','','','','','','','','','','','','','','');
	$content = str_replace($search_pattern, $replace_pattern, $content);
	$keywords = explode(' ',$content);
	$keywords = array_unique($keywords);

	foreach($keywords as $keyword) {
		if(strlen($keyword)>=$limit && !is_numeric($keyword)) {
			$tmp[] = $keyword;
		}
	}

	$tmp = array_slice($tmp,0,40);	// If you want to change the limit of keywords, change this number from 40 to what you want
	if(count($tmp)>0) {
		return implode(',',$tmp);
	} else {
		if(!isset($config_handler) || !is_object($config_handler)) {
			$config_handler =& xoops_gethandler('config');
		}
		$xoopsConfigMetaFooter =& $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
		if(isset($xoopsConfigMetaFooter['meta_keywords'])) {
			return $xoopsConfigMetaFooter['meta_keywords'];
		}
	}
}

/**
 * Retreive an editor according to the module's option "form_options"
 */
function &userpage_getWysiwygForm($caption, $name, $value = '', $width = '100%', $height = '400px', $supplemental='')
{
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$editor = false;
	$x22 = false;
	$xv = str_replace('XOOPS ','',XOOPS_VERSION);
	if(substr($xv,2,1) == '2') {
		$x22 = true;
	}
	$editor_configs = array();
	$editor_configs['name'] = $name;
	$editor_configs['value'] = $value;
	$editor_configs['rows'] = 35;
	$editor_configs['cols'] = 60;
	$editor_configs['width'] = $width;
	$editor_configs['height'] = $height;

	switch(strtolower(userpage_getmoduleoption('usekiovi'))) {
		case 'spaw':
			if(!$x22) {
				if (is_readable(XOOPS_ROOT_PATH . '/class/spaw/formspaw.php'))	{
					include_once(XOOPS_ROOT_PATH . '/class/spaw/formspaw.php');
					$editor = new XoopsFormSpaw($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, 'spaw', $editor_configs);
			}
			break;

		case 'fck':
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php'))	{
					include_once(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php');
					$editor = new XoopsFormFckeditor($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, 'fckeditor', $editor_configs);
			}
			break;

		case 'htmlarea':
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php'))	{
					include_once(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php');
					$editor = new XoopsFormHtmlarea($caption, $name, $value);
				}
			} else {
				$editor = new XoopsFormEditor($caption, 'htmlarea', $editor_configs);
			}
			break;

		case 'dhtml':
			if(!$x22) {
				$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
			} else {
				$editor = new XoopsFormEditor($caption, 'dhtmltextarea', $editor_configs);
			}
			break;

		case 'textarea':
			$editor = new XoopsFormTextArea($caption, $name, $value);
			break;

		case 'tinyeditor':
			if ( is_readable(XOOPS_ROOT_PATH.'/class/xoopseditor/tinyeditor/formtinyeditortextarea.php')) {
				include_once XOOPS_ROOT_PATH.'/class/xoopseditor/tinyeditor/formtinyeditortextarea.php';
				$editor = new XoopsFormTinyeditorTextArea(array('caption'=> $caption, 'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'400px'));
			}
			break;

		case 'koivi':
			if(!$x22) {
				if ( is_readable(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php')) {
					include_once(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php');
					$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px', '');
				}
			} else {
				$editor = new XoopsFormEditor($caption, 'koivi', $editor_configs);
			}
			break;
		}
		return $editor;
}

/**
 * Create (in a link) a javascript confirmation's box
 *
 * @param string $msg	Message to display
 * @param boolean $form	Is it a confirmation's message for a form ?
 * @return string The javascript "command" to use in the link
 */
function userpage_JavascriptLinkConfirm($msg, $form = false)
{
	if(!$form) {
		return "onclick=\"javascript:return confirm('".str_replace("'"," ",$msg)."')\"";
	} else {
		return "onSubmit=\"javascript:return confirm('".str_replace("'"," ",$msg)."')\"";
	}
}

?>