<?php
/**
 * ****************************************************************************
 * userpage - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         userpage
 * @author 			Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

$modversion['name'] = _MI_USERPAGE_NAME;
$modversion['version'] = 1.5;
$modversion['description'] = _MI_USERPAGE_DESC;
$modversion['credits'] = "DefianceB0y, Riosoft, Shine, Gibaphp, Feichtl and Calidro (and all the others) for the quick translations !";
$modversion['author'] = 'Instant Zero - http://xoops.instant-zero.com';
$modversion['help'] = "";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
$modversion['image'] = "images/logo_userpage.png";
$modversion['dirname'] = "userpage";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = "userpage";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Templates
$modversion['templates'][1]['file'] = 'userpage_index.html';
$modversion['templates'][1]['description'] = 'Show and edit a user page and show a list of all pages';
$modversion['templates'][2]['file'] = 'userpage_rss.html';
$modversion['templates'][2]['description'] = 'RSS Feed';
$modversion['templates'][3]['file'] = 'userpage_edit.html';
$modversion['templates'][3]['description'] = "Form used to edit a user's page";
$modversion['templates'][4]['file'] = 'userpage_list.html';
$modversion['templates'][4]['description'] = "Show a list of user's page";


// Blocks
$modversion['blocks'][1]['file'] = "userpage_last.php";
$modversion['blocks'][1]['name'] = _MI_USERPAGE_BNAME1;
$modversion['blocks'][1]['description'] = "Show last pages";
$modversion['blocks'][1]['show_func'] = "b_userpage_last_show";
$modversion['blocks'][1]['edit_func'] = "b_userpage_last_edit";
$modversion['blocks'][1]['options'] = "10|30";	// 10=Items count, 30=Title's length
$modversion['blocks'][1]['template'] = 'userpage_block_last.html';

$modversion['blocks'][2]['file'] = "userpage_top.php";
$modversion['blocks'][2]['name'] = _MI_USERPAGE_BNAME2;
$modversion['blocks'][2]['description'] = "Show most viewed pages";
$modversion['blocks'][2]['show_func'] = "b_userpage_top_show";
$modversion['blocks'][2]['edit_func'] = "b_userpage_top_edit";
$modversion['blocks'][2]['options'] = "10|30";	// 10=Items count, 30=Title's length
$modversion['blocks'][2]['template'] = 'userpage_block_top.html';

$modversion['blocks'][3]['file'] = "userpage_random.php";
$modversion['blocks'][3]['name'] = _MI_USERPAGE_BNAME3;
$modversion['blocks'][3]['description'] = "Show random pages";
$modversion['blocks'][3]['show_func'] = "b_userpage_random_show";
$modversion['blocks'][3]['edit_func'] = "b_userpage_random_edit";
$modversion['blocks'][3]['options'] = "10|30";	// 10=Items count, 30=Title's length
$modversion['blocks'][3]['template'] = 'userpage_block_random.html';

// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_USERPAGE_MENU1;
$modversion['sub'][1]['url'] = "userpage_list.php";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "userpage_search";

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'page_id';


/**
 * Allow html ?
 */
$modversion['config'][1]['name'] = 'allowhtml';
$modversion['config'][1]['title'] = '_MI_USERPAGE_OPT0';
$modversion['config'][1]['description'] = '_MI_USERPAGE_OPT0_DSC';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;

/**
 * Allow RSS Feeds ?
 */
$modversion['config'][2]['name'] = 'allowrss';
$modversion['config'][2]['title'] = '_MI_USERPAGE_OPT1';
$modversion['config'][2]['description'] = '_MI_USERPAGE_OPT1_DSC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

/**
 * Date's format. If you don't specify anything then the default date's format will be used
 */
$modversion['config'][3]['name'] = 'dateformat';
$modversion['config'][3]['title'] = '_MI_USERPAGE_OPT3';
$modversion['config'][3]['description'] = '_MI_USERPAGE_OPT3_DSC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = '';

/**
 * Number of characters to use in the RSS feed
 */
$modversion['config'][4]['name'] = 'rsslength';
$modversion['config'][4]['title'] = '_MI_USERPAGE_OPT4';
$modversion['config'][4]['description'] = '_MI_USERPAGE_OPT4_DSC';
$modversion['config'][4]['formtype'] = 'textbox';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 200;

/**
 * Number of lines per page
 */
$modversion['config'][5]['name'] = 'linesperpage';
$modversion['config'][5]['title'] = '_MI_USERPAGE_OPT5';
$modversion['config'][5]['description'] = '_MI_USERPAGE_OPT5_DSC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 10;

/**
 * Editor to use
 */
$modversion['config'][6]['name'] = 'usekiovi';
$modversion['config'][6]['title'] = '_MI_USERPAGE_OPT6';
$modversion['config'][6]['description'] = '_MI_USERPAGE_OPT6_DSC';
$modversion['config'][6]['formtype'] = 'select';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = 'dhtml';
xoops_load('xoopseditorhandler');
$modversion['config'][6]['options'] = array_flip(xoopsEditorHandler::getList());



/**
 * Allow html ?
 */
$modversion['config'][7]['name'] = 'url_rewriting';
$modversion['config'][7]['title'] = '_MI_USERPAGE_URL_REWRITING';
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 0;


// Notifications
$modversion['hasNotification'] = 0;
?>