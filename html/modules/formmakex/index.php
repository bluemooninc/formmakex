<?php
// $Id: index.php,v 0.82 2007/12/04 12:40:03 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                      formmakex - Bluemoon Multi-Form                     //
//                   Copyright (c) 2005 - 2007 Bluemoon inc.                 //
//                       <http://www.bluemooninc.biz/>                       //
//              Original source by : phpESP V1.6.1 James Flemer              //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
include_once('./conf.php');
include_once('admin/phpESP.ini.php');
include_once('./class/formmakexTable.class.php');
include_once('./class/formmakexStatus.class.php');

$sid = $manage_on = 0;
if(isset($_POST['sid'])) $sid = intval($_POST['sid']);
elseif(isset($_GET['sid'])) $sid = intval($_GET['sid']);
//$_SESSION['formmakex']=array();
// display control
$manage_on = isset($_SESSION['formmakex']['manage_on']) ? $_SESSION['formmakex']['manage_on'] : -1;
$sortby    = isset($_SESSION['formmakex']['sortby'   ]) ? $_SESSION['formmakex']['sortby'   ] : NULL;
$order     = isset($_SESSION['formmakex']['order'    ]) ? $_SESSION['formmakex']['order'    ] : 'DESC';
$status    = isset($_SESSION['formmakex']['status'   ]) ? $_SESSION['formmakex']['status'   ] : "0,1,2,4,8";
// get from usesr
$manage_on = isset($_GET['manage_on']) ? intval($_GET['manage_on']) : $manage_on;
$sortby = isset($_GET['sortby']) ? htmlspecialchars ( rawurldecode($_GET['sortby']) , ENT_QUOTES ) : 'changed';
$order = isset($_GET['order']) ? htmlspecialchars ( rawurldecode($_GET['order']) , ENT_QUOTES ) : 'desc';
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$altorder = ($order=='asc') ? 'desc' : 'asc';
if (isset($_GET['status'])){
	if( is_array($_GET['status'])){
		$status = implode($_GET['status'],",");
	}else{
		$status = isset($_GET['status']) ? htmlspecialchars ( $_GET['status'], ENT_QUOTES ) : '1';
	}
}
// For Guest
if (!$xoopsUser) $status=1;
// stock to session
$_SESSION['formmakex']['manage_on'] = $manage_on;
$_SESSION['formmakex']['sortby'] = $sortby;
$_SESSION['formmakex']['order'] = $order;
$_SESSION['formmakex']['status'] = $status;
// Check if the user as admin right to the module

$fmxStatus = new formmakexStatus($status);

$tpl_vars = array(
	'content' => array(),
	'langs' => array(
		'pagetitle' => FORMMAKEX_INDEX_PAGETITLE,
		'pagesubtitle' => $fmxStatus->statusTitle,
		'form_title' => _MB_LIST_TITLE,
		'form_subtitle' => _MB_LIST_SUBTITLE,
		'form_name' => _MB_LIST_NAME,
		'form_owner' => _MB_LIST_OWNER,
		'form_submitted' => _MB_LIST_SUBMITTED,
		'col_data' => _MB_LIST_COL_DATA,
		'col_results' => _MB_LIST_COL_RESULTS,
		'col_results_analyze' => _MB_LIST_COL_RESULTS_ANALYZE,
		'col_results_respondents' => _MB_LIST_COL_RESULTS_RESPONDENTS,
		'col_results_spreadsheet' => _MB_LIST_COL_RESULTS_SPREADSHEET,
		'col_results_cross' => _MB_LIST_COL_RESULTS_CROSS,
		'col_results_download' => _MB_LIST_COL_RESULTS_DOWNLOAD,
		'col_control' => _MB_LIST_COL_CONTROL,
		'col_control_modify' => _MB_LIST_COL_CONTROL_MODIFY,
		'col_control_status' => _MB_LIST_COL_CONTROL_STATUS,
		'col_control_access' => _MB_LIST_COL_CONTROL_ACCESS,
		'col_control_access_public' => _MB_LIST_COL_CONTROL_ACCESS_PUBLIC,
		'col_control_access_limited' => _MB_LIST_COL_CONTROL_ACCESS_LIMITED,
		'col_control_access_2public' => _MB_LIST_COL_CONTROL_ACCESS_2PUBLIC,
		'col_control_access_2limited' => _MB_LIST_COL_CONTROL_ACCESS_2LIMITED,
		'col_control_access_setperm' => _MB_LIST_COL_CONTROL_ACCESS_SETPERM,
		'col_control_access' => _MB_LIST_COL_CONTROL_ACCESS,
		'col_control_copy' => _MB_LIST_COL_CONTROL_COPY,
		'col_control_edit' => _MB_LIST_COL_CONTROL_EDIT
	),
	'config' => array(
		'listview' => 'simple',
		'formManager'=>$fmxStatus->formManager,
		'is_mod_admin'=>$fmxStatus->is_mod_admin
	)
);
$langs = get_defined_constants();
$formTable = new formmakexTable($sid);
$formTable->setPageStart($start);
if (!$sid){
	if($xoopsUser && $manage_on){
		//$uid = $fmxStatus->is_mod_admin ? 0 : $xoopsUser->uid();
		$tpl_vars['content']['forms'] = $formTable->get_form_list($sid, FALSE, $sortby, $order, $status);
		$tpl_vars['content']['pagenavi'] = $formTable->pageNavi(10);
		
	}else{
		$tpl_vars['content']['forms'] = $formTable->get_form_list($sid, FALSE, $sortby, $order, $status);
		$tpl_vars['content']['pagenavi'] = $formTable->pageNavi(10);
	}
	$tpl_vars['content']['sortnavi'] = $formTable->sortNavi();
	$xoopsOption['template_main'] = 'formmakex_index.html';
}else{
	$tpl_vars['content']['form'] = $formTable->formInfo;
	$xoopsOption['template_main'] = 'formmakex_controlpanel.html';
}
$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" media="screen,tv,print" href="style.css" />');
$xoopsTpl->assign('manage_on', $manage_on);
$xoopsTpl->assign('order', $altorder);
$xoopsTpl->assign('formmakex', $tpl_vars);
include(XOOPS_ROOT_PATH.'/footer.php');
?>
