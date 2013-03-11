<?php
/**
 * ****************************************************************************
 * userpage - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard (http://www.herve-thouzard.com/)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         userpage
 * @author 			Hervé Thouzard (http://www.herve-thouzard.com/)
 *
 * Version : $Id:
 * ****************************************************************************
 */
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("USERPAGE_DIRNAME") ) {
	define("USERPAGE_DIRNAME", 'userpage');
	define("USERPAGE_URL", XOOPS_URL.'/modules/'.USERPAGE_DIRNAME.'/');
	define("USERPAGE_PATH", XOOPS_ROOT_PATH.'/modules/'.USERPAGE_DIRNAME.'/');
	define("USERPAGE_IMAGES_URL", USERPAGE_URL.'images/');
	define("USERPAGE_IMAGES_PATH", USERPAGE_PATH.'images/');
}

// Chargement des handler et des autres classes
require_once USERPAGE_PATH.'class/userpage_utils.php';
?>