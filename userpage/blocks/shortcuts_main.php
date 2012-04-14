<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

include_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';

function b_shortcuts_show($options)
{
	global $xoopsUser, $xoopsTpl;
	$block = array();
	$block['addlink']=XOOPS_URL.'/modules/shortcuts/add.php';
	$urlscript = '';
	$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');

	if(xoops_trim($_SERVER['REQUEST_URI'])!='') {
		$urlscript=$_SERVER['REQUEST_URI'];
	} else {
		if(xoops_trim($_SERVER["SCRIPT_NAME"])!='') {
			$urlscript=$_SERVER["SCRIPT_NAME"];
		} else {
			if(xoops_trim($_SERVER["URL"])!='') {
				$urlscript=$_SERVER["URL"];
			} else {
				if(xoops_trim($_SERVER["PHP_SELF"])!='') {
					$urlscript=$_SERVER["PHP_SELF"];
				}
			}
		}
	}
	if(stristr(XOOPS_URL.'/modules/shortcuts/add.php?Op=Add', $urlscript) === false) {
		$_SESSION['shortcuts_url'] = $urlscript;
		$_SESSION['shortcuts_pagetitle'] = $xoopsTpl->get_template_vars('xoops_pagetitle');
	}
	$block['cururl_nonencoded'] = $urlscript;
	$block['cururl'] = urlencode($urlscript);
	$block['lang_go'] = _GO;
	$block['lang_add_current_page'] = _SHORTCUT_ADD_CURRENT_URL;
	$block['lang_view_shortcut'] = _SHORTCUT_VIEW_SHORTCUT;
	$block['lang_edit_shortcuts'] = _SHORTCUT_EDIT_SHORTCUTS;
	$block['display_layout'] = $options[0];
	$block['go_link']=XOOPS_URL.'/modules/shortcuts/redirect.php';

	$jscssurl=XOOPS_URL.'/modules/shortcuts/jscss/';
	$block['jscssurl'] = $jscssurl;
	switch($options[0]) {
		case 2:
			$urlcss = $jscssurl.'contextual.css';
			if(is_object($xoopsTpl)) {
				$xoopsTpl->assign("xoops_module_header", "<link rel=\"stylesheet\" type=\"text/css\" href=\"$urlcss\" />");
			}
			break;
	}

	$shortcutscount = 0;
	if(is_object($xoopsUser)) {
		$shortcutarr = array();
		$maxshortcuts = st_getmoduleoption('maxshortcuts');
		$savehits = st_getmoduleoption('savehits');

		$tblsort = array('date','title','url','rating');

		$critere = new Criteria('uid', $xoopsUser->getVar('uid'),'=');
		$critere->setSort($tblsort[st_getmoduleoption('sortorder')]);
		$shortcutarr = $shortcuts_handler->getObjects($critere);
		$shortcutscount = count($shortcutarr);
    	foreach ($shortcutarr as $one_shortcut) {
        	$block['shortcuts'][]=Array(
       				'date' => formatTimestamp($one_shortcut->getVar('date')),
       				'title' => $one_shortcut->getVar('title'),
       				'url' =>  $one_shortcut->getVar('url'),
       				'hits' => $one_shortcut->getVar('hits'),
       				'rating' => $one_shortcut->getVar('rating'),
       				'id' => $one_shortcut->getVar('shortcutid'));
   		}
	}
	$block['shortcuts_count']=$shortcutscount;
	return $block;
}



function b_shortcuts_edit($options)
{
    $form = _SHORTCUT_DISPLAY_LAYOUT."&nbsp;<select name='options[]'>";
    $form .= "<option value='0'";
    if ( $options[0] == 0 ) {
        $form .= " selected='selected'";
    }
    $form .= ">"._SHORTCUT_DISPLAY_1."</option>\n";

    $form .= "<option value='1'";
    if($options[0] == 1){
        $form .= " selected='selected'";
    }
    $form .= ">"._SHORTCUT_DISPLAY_2."</option>";

    $form .= "<option value='2'";
    if ( $options[0] == 2 ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _SHORTCUT_DISPLAY_3 . "</option>";

    $form .= "<option value='3'";
    if ( $options[0] == 3 ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _SHORTCUT_DISPLAY_4 . "</option>";

    $form .= "<option value='4'";
    if ( $options[0] == 4 ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _SHORTCUT_DISPLAY_5 . "</option>";

    $form .= "</select>\n";
    return $form;
}
?>
