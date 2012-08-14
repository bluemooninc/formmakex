<?php
// $Id: formmakexTable.class.php,v0.83 2008/01/08 18:38:03 yoshis Exp $
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
class formmakexTable {
	var $publicShowToList = 0;
	var $lastFormId =0;
	var $total = 0;
	var $start = 0;
	var $perpage = 10;
	var $sortname = '';
	var $sortorder = '';
	var $sortorderStr = array('ASC'=>'DESC','DESC'=>'ASC');
	var $status = '1';
	var $stat_flag;
	var $stat_desc;
	var $userGroups;
	var $ownerGroup;
	var $ownerUid;
	var $is_admin;
	var $editbyGroup=false;
	var $viewbyGroup=false;
	var $formInfo;
	var $message;
	
	function formmakexTable($sid=0, $formName=''){
		global $xoopsUser,$xoopsModule,$xoopsModuleConfig;
		
		$thsi->publicShowToList = $xoopsModuleConfig['SHOW_PUBLIC_TO_OTHERGROUP'];

		$this->is_admin =false;
		$this->userGroups = array();
		if ($xoopsUser){
			$this->userGroups = $xoopsUser->getGroups();
			if ( is_object($xoopsModule)  ) {
				$this->is_admin = $xoopsUser->isAdmin( $xoopsModule->mid() );
			}
		}
		if ($sid){
			$this->formInfo = $this->get_formInfoById($sid);
			$this->ownerGroup = $this->formInfo['realm'];
		}elseif ($formName){
			$this->formInfo = $this->get_formInfoByName($formName);
		}
	}
	function editbyGroup(){
		return $this->editbyGroup;
	}
	function viewbyGroup(){
		return $this->viewbyGroup;
	}
	function isOwnerGroup(){
		global $xoopsUser;
		$groups = $xoopsUser->getGroups();
		return in_array($this->ownerGroup,$groups);
	}
	function get_formInfo($sql){
		global $xoopsDB;
		$result = $xoopsDB->query($sql);
		$row = $xoopsDB->fetchArray($result);
		$row['uid'] = $row['owner'];
		if (isset($row['last_update'])){
			$row['last_update_s'] = formatTimestamp($row['last_update'], 's');
			$row['last_update_m'] = formatTimestamp($row['last_update'], 'm');
			$row['last_update_l'] = formatTimestamp($row['last_update'], 'l');
		}
		if(isset($row['status'])) $this->get_status($row['status']);
		$row['status'] = $this->stat_flag;
		$row['status_desc'] = $this->stat_desc;
		if(isset($row['realm']) && isset($row['owner'])){
			$this->ownerGroup = $row['realm'];
			$this->ownerUid = $row['owner'];
			$this->set_manageFlag($row['owner'],$row['realm']);
		}
		$row['editbyGroup'] = $this->editbyGroup;
		$row['viewbyGroup'] = $this->viewbyGroup;
		return $row;
	}
	function get_formInfoById($sid){
		global $xoopsDB;
		$sql = "SELECT *, UNIX_TIMESTAMP(changed) AS last_update FROM ".TABLE_FORM." WHERE id='".$sid."'";
		$row = $this->get_formInfo($sql);
		// Extra info for edit
		$userHander = new XoopsUserHandler($xoopsDB);
		$row['uname'] = ($tUser = $userHander->get($row['owner'])) ? $tUser->uname() : '';
		$row['resp'] = $this->get_responseCount($row['id']);
//		var_dump($row); die;
		return $row;
	}
	function get_formInfoByName($formName){
		$sql = "SELECT *, UNIX_TIMESTAMP(changed) AS last_update FROM ".TABLE_FORM." WHERE name='".$formName."'";
		$row = $this->get_formInfo($sql);
		return $row;
	}
	function set_response_id($rid,$sid){
		global $xoopsDB;
		$sql = "UPDATE ".TABLE_FORM." SET response_id = '${rid}' WHERE id='${sid}'";
		$result = $xoopsDB->query($sql);
		if ($result) $this->message = _MD_DEFAULTRESULTDONE;
	}
	function get_responseCount($sid){
		global $xoopsDB;
		$sql = "SELECT count(*) FROM ".TABLE_RESPONSE." WHERE complete='Y' AND form_id='".$sid."'";
		$result = $xoopsDB->query($sql);
		list($cnt) = $xoopsDB->fetchrow($result);
		return $cnt;
	}
	function set_manageFlag($ownerId,$ownerGp){
		global $xoopsUser;
		if ( $this->is_admin ){
			$this->editbyGroup = true;
			$this->viewbyGroup = true;
		}else{
			$this->editbyGroup = false;
			$this->viewbyGroup = false;
		}
		if($xoopsUser){
			if ( $ownerId == $xoopsUser->uid()){
				$this->editbyGroup = true;
				$this->viewbyGroup = true;
			}elseif (in_array($ownerGp,$this->userGroups)){
				$this->editbyGroup = true;
				$this->viewbyGroup = true;
			}
		}
	}
	/* {{{ proto boolean auth_is_owner(int formId, string user)
	   Returns true if user owns the form. */
	function auth_is_owner($sid, $user) {
		global $xoopsDB;
		$val = false;
		$sql = "SELECT s.owner = '$user' FROM ".TABLE_FORM." s WHERE s.id='$sid'";
		$result = $xoopsDB->query($sql);
		if(!(list($val) = $xoopsDB->fetchRow($result)))
			$val = false;
		
		return $val;
	}
	/* }}} */
	
	/* {{{ proto string auth_get_form_realm(int formId)
	   Returns the realm of the form. */
	function auth_get_form_realm($sid) {
		global $xoopsDB;
	
		$val = '';
		$sql = "SELECT s.realm FROM ".TABLE_FORM." s WHERE s.id='$sid'";
		$result = $xoopsDB->query($sql);
		list($val) = $xoopsDB->fetchRow($result);
		
		return $val;
	}
	/* }}} */
	
	/* {{{ proto boolean auth_no_access(string description)
	   Handle a user trying to access an unauthorised area.
	   Returns true if user should be allowed to continue.
	   Returns false (or exits) if access should be denied. */
	function auth_no_access($description) {
		//echo($formRender->mkerror(_MB_This_account_does_not_have_permission .' '. $description .'.'));
		echo("permission error\n<br>\n");
		echo("<a href=\"". $GLOBALS['FMXCONFIG']['manage'] ."?where=manage\">" . _MB_Go_back_to_Management_Interface . "</a>\n");
		return false;
	}
	/* }}} */	
	function log($str){
		//if( $FMXCONFIG['log_output'] == 1 ){
			$log = './log/form.log';
			$fp = fopen($log, 'a');
			fwrite($fp, $str."\n");
			fclose($fp);
		//}
	}
	
	function getDateFromHttpParams(){
		
		$param = isset($_POST['param']) ? ($_POST['param']) : 0;
		if($param == 0){
			$param = isset($_GET['param']) ? ($_GET['param']) : 0;
		}
		/* It doesn't work with php4isapi.dll.
		if($param == 0){
			$tmp = explode('/', $_SERVER['REQUEST_URI']);
			$param = ($tmp[count($tmp)-1]);
		}*/
		$param = trim($param);
		
		if($param == 0){
			return false;
		}
		$result = array();
		$result['params'] = $param;
		//print("$param");
		if(preg_match("/^([0-9]+)-([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})-([a-zA-Z0-9]*)/", $param, $m)){
			$result['uid'] = formmakexTable::checkUid($m[1]);
			$result['year'] = formmakexTable::checkYear($m[2]);
			$result['month'] = formmakexTable::checkMonth($m[3]);
			$result['date'] = formmakexTable::checkDate($m[2], $m[3], $m[4]);
			$result['hours']=$m[5];
			$result['minutes']=$m[6];
			$result['seconds']=$m[7];
			$result['command'] = trim($m[8]);		// enc type for MT user
		}else if(preg_match("/^([0-9]+)-([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", $param, $m)){
			$result['uid'] = formmakexTable::checkUid($m[1]);
			$result['year'] = formmakexTable::checkYear($m[2]);
			$result['month'] = formmakexTable::checkMonth($m[3]);
			$result['date'] = formmakexTable::checkDate($m[2], $m[3], $m[4]);
			//print("$m[5]:$m[6]:$m[7]");
			$result['hours']=$m[5];
			$result['minutes']=$m[6];
			$result['seconds']=$m[7];
		}else if(preg_match("/^([0-9]+)-([0-9]{4})([0-9]{2})/", $param, $m)){
			$result['uid'] = formmakexTable::checkUid($m[1]);
			$result['year'] = formmakexTable::checkYear($m[2]);
			$result['month'] = formmakexTable::checkMonth($m[3]);
		}else if(preg_match("/^([0-9]+)/", $param, $m)){
			$result['uid'] = formmakexTable::checkUid($m[1]);
		}else{
			redirect_header(XOOPS_URL.'/',1,_MD_POPNUPBLOG_INVALID_DATE.'(INVALID PARAM)');
			exit();
		}
		return $result;
	}
	
	function getApplicationNum(){
		global $xoopsDB;
		if(!$dbResult = $xoopsDB->query('select count(*) num from '.POPNUPBLOG_TABLE_APPL)){
			return 0;
		}
		if(list($num) = $xoopsDB->fetchRow($dbResult)){
			return $num;
		}
		return 0;
	}
	
	function weformUpdatesPing($rss, $url, $form_name = null, $title = null, $excerpt = null){
		$ping = new formmakexPing2($rss, $url, $form_name, $title, $excerpt);
		$ping->send();
		/* debug log
		ob_start();
		print_r($ping);
		$log = ob_get_contents();
		ob_end_clean();
		formmakexTable::log($log);
		*/
	}
	
	function newApplication($in_title, $in_permission){
		global $xoopsUser, $xoopsDB;
		$title = "";
		$permission = -1;
		if(!empty($in_title)){
			$title = formmakexTable::convert2sqlString($in_title);
		}
		if( ($in_permission == 0) || ($in_permission == 1) || ($in_permission == 2) || ($in_permission == 3)){
			$permission = intval($in_permission);
		}
		
		if($permission < 0){
			return _MD_POPNUPBLOG_ERR_INVALID_PERMISSION;
		}
		if(!$result = $xoopsDB->query('select uid from '.POPNUPBLOG_TABLE_APPL.' where uid = '.$xoopsUser->uid())){
			return "select error";
		}
		if(list($tmpUid) = $xoopsDB->fetchRow($result)){
			return _MD_POPNUPBLOG_ERR_APPLICATION_ALREADY_APPLIED;
		}
		if(!$result = $xoopsDB->query('select uid from '.POPNUPBLOG_TABLE_INFO.' where uid = '.$xoopsUser->uid())){
			return "select error";
		}
		if(list($tmpUid) = $xoopsDB->fetchRow($result)){
			return _MD_POPNUPBLOG_ERR_ALREADY_WRITABLE;
		}
		$sql = sprintf("insert into %s (uid, title, permission, create_date) values(%u, '%s', %u, CURRENT_TIMESTAMP())", 
			POPNUPBLOG_TABLE_APPL, $xoopsUser->uid(), $title, $permission);
		if(!$result = $xoopsDB->query($sql)){
			return "insert error";
		}
		
		return "";
	}
	
	function getXoopsModuleConfig($key){
		global $xoopsDB,$mydirname;
		$mid = -1;

		$sql = "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname = '{$mydirname}'";
		if (!$result = $xoopsDB->query($sql)) {
			return false;
		}
		$numrows = $xoopsDB->getRowsNum($result);
		if ($numrows == 1) {
			list($l_mid) = $xoopsDB->fetchRow($result);
			$mid = $l_mid;
		}else{
			return false;
		}
		$sql = "select conf_value from ".$xoopsDB->prefix('config')." where conf_modid = ".$mid." and conf_name = '".trim($key)."'";
		if (!$result = $xoopsDB->query($sql)) {
			return false;
		}
		$numrows = $xoopsDB->getRowsNum($result);
		if ($numrows == 1) {
			list($value) = $xoopsDB->fetchRow($result);
			//return intval($value);
			return $value;
		}else{
			return false;
		}
	}
	//
	function get_status($status){
		if($status & STATUS_DELETED) {
			$this->stat_flag = STATUS_DELETED;
			//$this->stat_desc = _MB_Archived;
		} elseif($status & STATUS_DONE) {
			$this->stat_flag = STATUS_DONE;
			//$this->stat_desc = _MB_Ended;
		} elseif($status & STATUS_ACTIVE) {
			$this->stat_flag = STATUS_ACTIVE;
			//$this->stat_desc = _MB_Active;
		} elseif($status & STATUS_TEST) {
			$this->stat_flag = STATUS_TEST;
			//$this->stat_desc = _MB_Testing;
		} else {
			$this->stat_flag = 0;
			//$this->stat_desc = _MB_Editing;
		}
		return $this->stat_flag;
	}
	//Page Navi
	function setPageStart($start){
		$this->start = $start;
	}
	function sortNavi(){
		$endNumber = $this->perpage < $this->total ? $this->perpage : $this->total;
		return array(
			'sortname'=>$this->sortname,
			'sortorder'=>$this->sortorder,
			'status'=>$this->status,
			'start'=>$this->start + 1,
			'end'=>$endNumber,
			'perpage'=>$this->perpage,
			'total'=>$this->total
		);
	}
	function pageNavi($offset){
		include XOOPS_ROOT_PATH.'/class/pagenav.php';
		$optparam = $this->sortname ? 'sortby='.$this->sortname : "";
		$optparam .= $this->sortorder ? '&order='.$this->sortorder : "";
		$nav = new XoopsPageNav($this->total,$this->perpage,$this->start,"start", $optparam );
		return $nav->renderNav($offset);
	}	
	// get form list for index.php, manage.php
	function get_form_list($formId = NULL, $limit = TRUE, $sortby = 'changed', $order = 'DESC', $status = 1, $uid = 0){
		global $xoopsUser, $xoopsDB, $xoopsModule;
		
		$this->perpage =  $this->getXoopsModuleConfig('BLOCKLIST');
		$this->sortname = in_array($sortby,array('changed','owner','title','name','status')) ? $sortby : "changed" ;
		$this->sortorder = preg_match("/DESC|ASC/i",$order) ? $order : "ASC";
		$this->status = $status;

		$uid = ($xoopsUser) ? $xoopsUser->uid() : 0;
		if($uid>0){
			$gHandler =& xoops_gethandler('member');
			$groupArr =& $gHandler->getGroupsByUser($uid);
		}else{
			$groupArr = null;
		}
		
		if ( !is_null($status) || $uid ) $sql_where =  ' WHERE (';
		else $sql_where =  '';
		if (strlen($status)>1)
			$sql_where .= 'status IN ('. $status  . ')';
		else
			$sql_where .= !is_null($status) ? 'status='. intval($status)  : '';
		if (!$xoopsUser->isadmin()){
			if ( !is_null($status) && $uid ) $sql_where .=  ' AND ';
			if ( $uid ) $sql_where .= '(owner='. intval($uid);
			if ( $groupArr ) $sql_where .= " OR realm IN (" . implode(",",$groupArr) .")";
			if ( $sql_where ) $sql_where = $sql_where . ")";
			if ( $this->publicShowToList==1 ) $sql_where .= " OR ( status=1 )";
		}
		$sql_where .= ")";
		//
		// Get Recordcount for page switching
		//
		$sql = sprintf('SELECT COUNT(*) FROM %s %s;', TABLE_FORM, $sql_where);
		//echo $sql; die;
		list($this->total) = $xoopsDB->fetchRow($xoopsDB->query($sql));
		if (!empty($formId)) {
			if( $this->sortorder == "DESC" ) {
				$operator_for_position = '>' ;
			} else {
				$this->sortorder = "ASC" ;
				$operator_for_position = '<' ;
			}		
			$result = $xoopsDB->query("SELECT COUNT(id) FROM ".TABLE_FORM
				." WHERE ".$sql_where." AND id $operator_for_position $formId");
			list($position) = $xoopsDB->fetchRow($result);
			$this->start = intval($position / $this->perpage) * $this->perpage;
		}		
		$sql = sprintf('SELECT *, UNIX_TIMESTAMP(changed) AS last_update FROM %s %s ORDER BY %s %s'
			, TABLE_FORM, $sql_where, $this->sortname , $this->sortorder);
		if(!$result = $xoopsDB->query($sql, $this->perpage, $this->start)) return false;
		$userHander = new XoopsUserHandler($xoopsDB);
		$tpl_vars = array();

		while($row = $xoopsDB->fetchArray($result)){
			if ($row['respondents']){
				if (!in_array($row['respondents'],$groupArr) && $row['owner']!=$uid) continue;
			}
			if ( $this->is_admin && !$status){
				$accessible = true;
			}else if ( $row['public'] == 'N' && $row['owner']!=$uid){
				$accessible = false;
				$acs_sql = "SELECT realm FROM ".TABLE_ACCESS." WHERE form_id = '".$row['id'];
				$acs_result = $xoopsDB->query($acs_sql);
				while(list($arealm) = $xoopsDB->fetchRow($acs_result)){
					if (in_array($arealm,$this->userGroups)){
						$accessible = true;
						break;
					}
				}
			}else{
				$accessible = true;
			}
/*			if ( $row['status']!=1 && $row['owner']!=$xoopsUser->uid() && !in_array($row['realm'],$groupArr)){
				$hidelist = 1;
			}else{ 
				$hidelist = 0;
			}*/
			$hidelist = 0;
			if($accessible==true && $hidelist==0){
				$resp = 0;
				$rids = array();
				$submitted = 0;
				$this->set_manageFlag($row['owner'],$row['realm']);
				$row['hidelist'] = $hidelist;
				$row['editbyGroup'] = $this->editbyGroup;
				$row['viewbyGroup'] = $this->viewbyGroup;
				if($xoopsUser){
					$sbm_sql = 'SELECT submitted FROM '.TABLE_RESPONSE.' WHERE form_id='.$row['id'].' and username="'.$xoopsUser->uname().'" ORDER BY submitted DESC';
					$sbm_result = $xoopsDB->query($sbm_sql);
					list($submitted) = $xoopsDB->fetchRow($sbm_result);
				}
				$row['submitted'] = $submitted;
				$row['uid'] = $row['owner'];
				$row['uname'] = ($tUser = $userHander->get($row['owner'])) ? $tUser->uname() : '';
//				$row['last_update'] = $row['changed'];
				$row['last_update_s'] = formatTimestamp($row['last_update'], 's');
				$row['last_update_m'] = formatTimestamp($row['last_update'], 'm');
				$row['last_update_l'] = formatTimestamp($row['last_update'], 'l');
				$this->get_status($row['status']);
				$row['status'] = $this->stat_flag;
				$row['status_desc'] = $this->stat_desc;
				$row['resp'] = $this->get_responseCount($row['id']);
				$tpl_vars[] = $row;
			}
		}
		return $tpl_vars;
	}
	function get_Respondentinfo( $unm ){
		global $xoopsDB;
		$sql = "SELECT * FROM ".TABLE_RESPONDENT." WHERE username='".$unm."'";
		$result = $xoopsDB->query($sql);
		if ($xoopsDB->getRowsNum($result) != 1) return(false);
		$ret = $xoopsDB->fetchArray($result);
		
		$ret['sid'] = $ret['form_id'];
		$ret['rid'] = $ret['response_id'];
		return $ret;
	}
	function delete_respondent( $username ){
		global $xoopsDB;
		$sql = "DELETE FROM ".TABLE_RESPONDENT." WHERE username='".$username."'";
		$result = $xoopsDB->query($sql);
		if(!$xoopsDB->query($sql)) {
			/* unsucessfull -- abort */
			echo _MB_Cannot_delete_account .$username.' ('. $xoopsDB->error() .')';
		}
	}
	function update_respondent( $respondent ){
		global $xoopsDB;
		$debug=0;
		
		if ($debug) print_r($respondent);
		$disabled = ($respondent['disabled']==1) ? 'Y' : 'N';
		$sql = "SELECT * FROM ".TABLE_RESPONDENT." WHERE username='".$respondent['username']."'";
		$result = $xoopsDB->query($sql);
		if ($xoopsDB->getRowsNum($result) != 1){
			$sql = sprintf("insert into %s 
				(username,password,fname,lname,email,disabled,form_id,response_id,changed,expiration)
				values('%s','%s','%s','%s','%s','%s',%u,%u,CURRENT_TIMESTAMP(),'%s')", 
				TABLE_RESPONDENT,
				$respondent['username'],
				$respondent['password'],
				$respondent['fname'],
				$respondent['lname'],
				$respondent['email'],
				$disabled,
				$respondent['sid'],
				$respondent['rid'],
				$respondent['expiration']);
		}else{
			$sql = "UPDATE ".TABLE_RESPONDENT." SET "
			."password='".$respondent['password']."'"
			.",fname='".$respondent['fname']."'"
			.",lname='".$respondent['lname']."'"
			.",email='".$respondent['email']."'"
			.",disabled='". $disabled ."'"
			.",form_id=".$respondent['sid']
			.",response_id=".$respondent['rid']
			.",changed='".$respondent['changed']."'"
			.",expiration='".$respondent['expiration']."'"
			." WHERE username='".$respondent['username']."'";
		}
		if ($debug) echo "<p>".$sql;
		$xoopsDB->queryF($sql);
	}
	
	function createRssURL($uid){
		if((empty($useRerite)) || ($useRerite == 0) ){
			return POPNUPBLOG_DIR.'rss.php'.POPNUPBLOG_REQUEST_URI_SEP.$uid;
		}else{
			return POPNUPBLOG_DIR.'rss/'.$uid.".xml";
		}
	}
	
	function createUrl($uid){
		return XOOPS_URL."/modules/'.$mydirname.'/";
	}
	
	function createUrlNoPath($uid, $year = 0, $month = 0, $date = 0, $hours = 0, $minutes = 0, $seconds = 0, $command = null){
		$result = '';
		if((empty($useRerite)) || ($useRerite == 0) ){
			$result .= "index.php".POPNUPBLOG_REQUEST_URI_SEP.formmakexTable::makeParams($uid, $year, $month, $date, $hours, $minutes, $seconds, $command);
		}else{
			$result .= "view/".formmakexTable::makeParams($uid, $year, $month, $date, $hours, $minutes, $seconds, $command).".html";
		}
		return $result;
	}
	
	function mb_strcut($text, $start, $end){
		if(function_exists('mb_strcut')){
			// return mb_strcut($text, $start, $end);
			return mb_substr($text, $start, $end);
		}else{
			return substr($text, $start, $end);
			// return strcut($text, $start, $end);
		}
	}
	
	function toRssDate($time, $timezone = null){
		if(!empty($timezone)){
			$time = xoops_getUserTimestamp($time, $timezone);
		}
		$res =  date("Y-m-d\\TH:i:sO", $time);
		// mmmm
		$result = substr($res, 0, strlen($res) -2).":".substr($res, -2);
		return $result;
	}
	
	function checkUid($iuid){
		$uid = intval($iuid);
		if( $uid > 0){
			return $uid;
		}
	}

	function checkYear($iyear){
		$year = intval($iyear);
		if ( ($year > 1000) && ($year < 3000) ){
			return $iyear;
		}
		redirect_header(XOOPS_URL.'/',1,_MD_POPNUPBLOG_INVALID_DATE.'(YEAR)'.$iyear);
		exit();
	}
	
	function checkMonth($imonth){
		$month = intval($imonth);
		if ( ($month > 0) && ($month < 13) ){
			return $imonth;
		}
		redirect_header(XOOPS_URL.'/',1,_MD_POPNUPBLOG_INVALID_DATE.'(MONTH)');
		exit();
	}
	
	function checkDate($year, $month, $date){
		if(checkdate(intval($month), intval($date), intval($year))){
			return $date;
		}
		redirect_header(XOOPS_URL.'/',1,_MD_POPNUPBLOG_INVALID_DATE.'(ALL DATE) '.intval($year)."-".intval($month)."-". intval($date));
		exit();
	}
	
	function makeParams($uid, $year=0, $month=0, $date=0, $hours=0, $minutes=0, $seconds=0, $command = null){
		$result = '';
		$c = '';
		if(!empty($command)){
			$c = '-'.$command;
		}
		if($year == 0){
			$result = $uid;
		}else if($date == 0){
			$result = sprintf("%s-%04u%02u%s", "".$uid, $year, $month, $c);
		}else{
			$result = sprintf("%s-%04u%02u%02u%02u%02u%02u%s", "".$uid, $year, $month, $date, $hours, $minutes, $seconds, $c);
		}
		return $result;
	}
	
	function makeTrackBackURL($uid, $year = 0, $month = 0, $date = 0, $hours=0, $minutes=0, $seconds=0){
		return XOOPS_URL.'/modules/popnupform/trackback.php'.POPNUPBLOG_REQUEST_URI_SEP.formmakexTable::makeParams($uid, $year, $month, $date, $hours, $minutes, $seconds);
	}
	
	function isCompleteDate($d){
		if(!empty($d['year'])){
			if(checkdate(intval($d['month']), intval($d['date']), intval($d['year']))){
				return true;
			}
		}
		return false;
	}
	function complementDate($d){
		if(!checkdate(intval($d['month']), intval($d['date']), intval($d['year']))){
			$time = time();
			$d['year'] = date('Y',$time);
			$d['month'] = sprintf('%02u', date('m',$time));
			$d['date'] =  sprintf('%02u', date('d',$time));
			$d['hours'] =  sprintf('%02u', date('H',$time));
			$d['minutes'] =  sprintf('%02u', date('i',$time));
			$d['seconds'] =  sprintf('%02u', date('s',$time));
		}
		//print($d['hours'].$d['minutes'].$d['seconds']);
		return $d;
	}
	
	function convert_encoding(&$text, $from = 'auto', $to){
		if(function_exists('mb_convert_encoding')){
			return mb_convert_encoding($text, $to, $from); 
		} else if(function_exists('iconv')){
			return iconv($from, $to, $text);
		}else{
			return $text;
		}
	}
	
	function assign_message(&$tpl){
		$all_constants_ = get_defined_constants();
		foreach($all_constants_ as $key => $val){
			if(preg_match("/^_(MB|MD|AM|MI)_FORMMAKEX_(.)*$/", $key) || preg_match("/^FORMMAKEX_(.)*$/", $key)){
				if(is_array($tpl)){
					$tpl[$key] = $val;
				}else if(is_object($tpl)){
					$tpl->assign($key, $val);
				}
			}
		}
	}
	/*
	function get_recent_trackback($date){
		global $xoopsDB;
		$sql = 'select title, url from '.POPNUPBLOG_TABLE_TRACKBACK.' where uid = '.$date['uid'].' order by t_date desc';
		if(!$db_result = $this->xoopsDB->query($sql)){
			return false;
		}
		$i = 0;
		
		$result['html'] = '<div>';
		while(list($title, $url) = $this->xoopsDB->fetchRow($db_result)){
			$result[data][] = new array(){ 'title' => $title, 'url' => $url};
			$i++;
			$result['html'] .= '<a href="'.$url.'" target="_blank">'.$title.'</a><br />';
		}
		$result['html'] .= '</div>';
		
		return $result;
	}
	*/
	function send_trackback_ping($trackback_url, $url, $title, $form_name, $excerpt = null) {
		formmakexPing2::send_trackback_ping($trackback_url, $url, $title, $form_name, $excerpt) ;
	}
	
	
	function remove_html_tags($t){
		return preg_replace_callback(
			"/(<[a-zA-Z0-9\"\'\=\s\/\-\~\_;\:\.\n\r\t\?\&\+\%\&]*?>|\n|\r)/ms", 
			/* "/(<[*]*?>|\n|\r)/ms", */
			"popnupform_remove_html_tags_callback", 
			$t);
	}
	
	
	function convert2sqlString($text){
		$ts =& MyTextSanitizer::getInstance();
		if(!is_object($ts)){
			exit();
		}
		$res = $ts->stripSlashesGPC($text);
		$res = $ts->censorString($res);
		$res = addslashes($res);
		return $res;
	}
	function mail_popimg(){
		global $log,$limit_min;
		if (filemtime($log) < time() - $limit_min * 60) {
			return "<div style=\"text-align:center;\"><img src=./pop.php?img=1&time=".time()."\" width=70 height=1 /></div>POPed";
		} else {
			return "snoozed";
		}
	}
	function get_mailcode(){
		switch (_LANGCODE){
		case "af": $code = "ISO-8859-1";break;	//Afrikaans
		case "ar": $code = "ISO-8859-6";break;	//Arabic
		case "be": $code = "ISO-8859-5";break;	//Byelorussian
		case "bg": $code = "ISO-8859-5";break;	//Bulgarian
		case "ca": $code = "ISO-8859-1";break;	//Catalan
		case "cs": $code = "ISO-8859-2";break;	//Czech
		case "da": $code = "ISO-8859-1";break;	//Danish
		case "de": $code = "ISO-8859-1";break;	//German
		case "el": $code = "ISO-8859-7";break;	//Greek
		case "en": $code = "us-ascii";	break;	//English
		case "eo": $code = "ISO-8859-3";break;	//Esperanto
		case "es": $code = "ISO-8859-1";break;	//Spanish
		case "eu": $code = "ISO-8859-1";break;	//Basque
		case "et": $code = "iso-8859-15";break;	//Estonian
		case "fi": $code = "ISO-8859-1";break;	//Finnish
		case "fo": $code = "ISO-8859-1";break;	//Faroese
		case "fr": $code = "ISO-8859-1";break;	//French
		case "ga": $code = "ISO-8859-1";break;	//Irish
		case "gd": $code = "ISO-8859-1";break;	//Scottish
		case "gl": $code = "ISO-8859-1";break;	//Galician
		case "hr": $code = "ISO-8859-2";break;	//Croatian
		case "hu": $code = "ISO-8859-2";break;	//Hungarian
		case "is": $code = "ISO-8859-1";break;	//Icelandic
		case "it": $code = "ISO-8859-1";break;	//Italian
		case "iw": $code = "ISO-8859-8";break;	//Hebrew
		case "ja": $code = "ISO-2022-JP";break;	//Japanese (Shift_JIS)
		case "ko": $code = "EUC_KR";	break;	//Korean	
		case "lt": $code = "ISO-8859-13";break;	//Lithuanian
		case "lv": $code = "ISO-8859-13";break;	//Latvian
		case "mk": $code = "ISO-8859-5";break;	//Macedonian
		case "mt": $code = "ISO-8859-5";break;	//Maltese
		case "nl": $code = "ISO-8859-1";break;	//Dutch
		case "no": $code = "ISO-8859-1";break;	//Norwegian
		case "pl": $code = "ISO-8859-2";break;	//Polish
		case "pt": $code = "ISO-8859-1";break;	//Portuguese
		case "ro": $code = "ISO-8859-2";break;	//Romanian
		case "ru": $code = "ISO-8859-5";break;	//Russian
		case "sh": $code = "ISO-8859-5";break;	//Serbo-Croatian
		case "sk": $code = "ISO-8859-2";break;	//Slovak
		case "sl": $code = "ISO-8859-2";break;	//Slovenian
		case "sq": $code = "ISO-8859-2";break;	//Albanian
		case "sr": $code = "ISO-8859-2";break;	//Serbian
		case "sv": $code = "ISO-8859-1";break;	//Swedish
		case "th": $code = "TIS620";	break;	//Thai
		case "tr": $code = "ISO-8859-9";break;	//Turkish
		case "uk": $code = "ISO-8859-5";break;	//Ukrainian
		case "zh": $code = "GB2312";	break;	//Chainese	
		default: $code = "UTF-8";break;
		}
		return $code;
	}
}
?>
