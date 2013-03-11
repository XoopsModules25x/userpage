<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once "../../mainfile.php";
error_reporting(0);
@$xoopsLogger->activated = false;

require_once XOOPS_ROOT_PATH.'/modules/userpage/fpdf/fpdf.inc.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

$myts =& MyTextSanitizer::getInstance();

$page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

if(empty($page_id)) {
   redirect_header('index.php', 2, _ERRORS);
   exit();
}

$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
$allowhtml = userpage_getmoduleoption('allowhtml');

$criteria = new Criteria('up_pageid', $page_id, '=');
$cnt = $userpage_handler->getCount($criteria);
if( $cnt>0 ) {
	$pagetbl = $userpage_handler->getObjects($criteria);
	$page = $pagetbl[0];
} else {	// Page not found
    redirect_header(XOOPS_URL.'/index.php',2,_USERPAGE_PAGE_NOT_FOUND);
	exit();
}
$page->setVar('dohtml',$allowhtml);

$pdf_title = $page->getVar('up_title');
$pdf_content = $page->getVar('up_text');
$pdf_author = $page->uname();
$pdf_topic_title = $page->getVar('up_title');
$pdf_title = $page->getVar('up_title');
$pdf_subtitle = '';
$pdf_subsubtitle = '';
$pdf_author = $page->uname();
$pdf_date = formatTimestamp($page->getVar('up_created'),userpage_getmoduleoption('dateformat'));
$pdf_url = XOOPS_URL.'/modules/userpage/index.php?page_id='.$page->getVar('up_pageid');
// ***************************************************************************************************************************************

$pdf_topic_title = userpage_html2text($myts->undoHtmlSpecialChars($pdf_topic_title));
$forumdata['topic_title'] = $pdf_topic_title;
$pdf_data['title'] = $pdf_title;
$pdf_data['subtitle'] = userpage_html2text($pdf_subtitle);
$pdf_data['subsubtitle'] = userpage_html2text($pdf_subsubtitle);
$pdf_data['date'] = $pdf_date;
$pdf_data['content'] = $myts->undoHtmlSpecialChars($pdf_content);
$pdf_data['author'] = $pdf_author;

//Other stuff
$puff='<br />';
$puffer='<br /><br /><br />';

//create the A4-PDF...
$pdf_config['slogan']=$xoopsConfig['sitename'].' - '.$xoopsConfig['slogan'];
$pdf_config['creator'] = 'USERPAGE - Instant Zero';
$pdf_config['url'] = $pdf_url;

$pdf=new PDF();
if(method_exists($pdf, "encoding")){
	$pdf->encoding($pdf_data, _CHARSET);
}
$pdf->SetCreator($pdf_config['creator']);
$pdf->SetTitle($pdf_data['title']);
$pdf->SetAuthor($pdf_config['url']);
$pdf->SetSubject($pdf_data['author']);
$out=$pdf_config['url'].', '.$pdf_data['author'].', '.$pdf_data['title'].', '.$pdf_data['subtitle'].', '.$pdf_data['subsubtitle'];
$pdf->SetKeywords($out);
$pdf->SetAutoPageBreak(true,25);
$pdf->SetMargins($pdf_config['margin']['left'],$pdf_config['margin']['top'],$pdf_config['margin']['right']);
$pdf->Open();

//First page
$pdf->AddPage();
$pdf->SetXY(24,25);
$pdf->SetTextColor(10,60,160);
$pdf->SetFont($pdf_config['font']['slogan']['family'],$pdf_config['font']['slogan']['style'],$pdf_config['font']['slogan']['size']);
$pdf->WriteHTML($pdf_config['slogan'], $pdf_config['scale']);
$pdf->Line(25,30,190,30);
$pdf->SetXY(25,35);
$pdf->SetFont($pdf_config['font']['title']['family'],$pdf_config['font']['title']['style'],$pdf_config['font']['title']['size']);
$pdf->WriteHTML($pdf_data['title'],$pdf_config['scale']);

if ($pdf_data['subtitle']<>''){
	$pdf->WriteHTML($puff,$pdf_config['scale']);
	$pdf->SetFont($pdf_config['font']['subtitle']['family'],$pdf_config['font']['subtitle']['style'],$pdf_config['font']['subtitle']['size']);
	$pdf->WriteHTML($pdf_data['subtitle'],$pdf_config['scale']);
}
if ($pdf_data['subsubtitle']<>'') {
	$pdf->WriteHTML($puff,$pdf_config['scale']);
	$pdf->SetFont($pdf_config['font']['subsubtitle']['family'],$pdf_config['font']['subsubtitle']['style'],$pdf_config['font']['subsubtitle']['size']);
	$pdf->WriteHTML($pdf_data['subsubtitle'],$pdf_config['scale']);
}

$pdf->WriteHTML($puff,$pdf_config['scale']);
$pdf->SetFont($pdf_config['font']['author']['family'],$pdf_config['font']['author']['style'],$pdf_config['font']['author']['size']);
$out=USERPAGE_PDF_AUTHOR.': ';
$out.=$pdf_data['author'];
$pdf->WriteHTML($out,$pdf_config['scale']);
$pdf->WriteHTML($puff,$pdf_config['scale']);
$out=USERPAGE_PDF_DATE;
$out.=$pdf_data['date'];
$pdf->WriteHTML($out,$pdf_config['scale']);
$pdf->WriteHTML($puff,$pdf_config['scale']);

$pdf->SetTextColor(0,0,0);
$pdf->WriteHTML($puffer,$pdf_config['scale']);

$pdf->SetFont($pdf_config['font']['content']['family'],$pdf_config['font']['content']['style'],$pdf_config['font']['content']['size']);
$pdf->WriteHTML($pdf_data['content'],$pdf_config['scale']);
$pdf->Output();
?>