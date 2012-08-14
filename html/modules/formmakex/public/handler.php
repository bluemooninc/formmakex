<?php
# $Id: handler.php,v 1.1.1.1 2005/08/10 12:14:04 yoshis Exp $
// Written by James Flemer
// For eGrad2000.com
// <jflemer@alum.rpi.edu>
/* When using the authentication for responses you need to include
 * part of the script *before* your template so that the
 * HTTP Auth headers can be sent when needed.
 *
 * See the handler .php file for details.
 */

require_once('./admin/phpESP.ini.php');
require_once('./conf.php');

$GLOBALS['errmsg'] = '';
/*
 * Security Check for GET parameter
 */
if(isset($_GET['sid'])) {
    $GLOBALS['errmsg'] = "handler [54]"._MB_Error_processing_form_Security_violation;
    return;
}
if(isset($_GET['results']) || isset($_POST['results'])) {
    $GLOBALS['errmsg'] = "handler [59]"._MB_Error_processing_form_Security_violation;
    return;
}

/*
 * Loading Form Infomation
 */
if (isset($_GET['name'])) {
	$formName = htmlspecialchars( $_GET['name'], ENT_QUOTES );
	$_SERVER['QUERY_STRING'] = ereg_replace('(^|&)name=[^&]*&?', '', $_SERVER['QUERY_STRING']);
	$formTable = new formmakexTable(0,$formName);
}else if (isset($_POST['sid']) && !empty($_POST['sid'])){
    $sid = intval($_POST['sid']);
	$formTable = new formmakexTable($sid);
}
if (isset($formTable->formInfo['id'])){
	$sid = $formTable->formInfo['id'];
}else{
	/*
	 * There is no longer your request survey
	 */
	if ($xoopsModuleConfig['INVALID_JUMPURI']!=""){
		$juri = preg_replace("/\[XOOPS_URL\]/i",XOOPS_URL,$xoopsModuleConfig['INVALID_JUMPURI']);
		redirect_header($juri,0,'Error');
	}else{
		redirect_header(XOOPS_URL,3,_MB_Form_not_specified);
	}
}
/*
 * User inout only one answer
*/
if( !isset($preview) ){
	if ($fmxSaver->response_submitted($sid)>0 ){
		$xoopsTpl->assign('message', _MB_LIST_SUBMITTED_DESC);
		require(XOOPS_ROOT_PATH.'/header.php');
		$xoopsOption['template_main'] = 'formmakex_message.html';
		include(XOOPS_ROOT_PATH.'/footer.php');
		exit;
	}
}

/*
 * Session Start if avoid as "sid" parameter
 */
if(!isset($sid) || empty($sid)) {
	$fmxEditForm->start_new();
    $GLOBALS['errmsg'] = "handler [69]". _MB_Form_not_specified ;
    return;
}
if ($xoopsUser) $_POST['userid'] = $xoopsUser->uid();
if(empty($_POST['userid'])) {
    // find remote user id (takes the first non-empty of the following)
    //  1. a GET variable named 'userid'
    //  2. the REMOTE_USER set by HTTP-Authentication
    //  3. the query string
    //  4. the remote ip address
    if (!empty($_GET['userid'])) {
        $_POST['userid'] = $_GET['userid'];
    } elseif(!empty($_SERVER['REMOTE_USER'])) {
        $_POST['userid'] = $_SERVER['REMOTE_USER'];
    } elseif(!empty($_SERVER['QUERY_STRING'])) {
        $_POST['userid'] = urldecode($_SERVER['QUERY_STRING']);
    } else {
        $_POST['userid'] = $_SERVER['REMOTE_ADDR'];
    }
}
if(empty($_POST['referer'])) $_POST['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
// Referer check as XOOPS_URL
if ( strpos($_POST['referer'], XOOPS_URL) !== 0 ) { $_POST['referer']=XOOPS_URL; }
if (empty($_POST['rid'])){
    $_POST['rid'] = '';
    $overWrite = false;
}else{
    $_POST['rid'] = intval($_POST['rid']) ? intval($_POST['rid']) : '';
	if (!isset($formTable)) $formTable = new formmakexTable($sid);
	if ($formTable->editbyGroup==TRUE){
        $overWrite = true;
	}else{
        $GLOBALS['errmsg'] = "handler [59]"._MB_Error_processing_form_Security_violation;
	}
}
if (empty($_POST['sec']))
    $_POST['sec'] = 1;
else
    $_POST['sec'] = (intval($_POST['sec']) > 0) ? intval($_POST['sec']) : 1;

$_page = isset($_POST['sec']) ? intval($_POST['sec']) : 0;
$_rid = isset($_POST['rid']) ? intval($_POST['rid']) : 0;
$_userid = $xoopsUser ? $xoopsUser->uid() : 0;

if (empty($_POST['referer'])) $_POST['referer'] = '';
// show results instead of show form
// but do not allow getting results from URL or FORM
/*
if(isset($results) && $results) {
	form_results($sid,$precision,$totals,$qid,$cids);
	return;
}
*/
// else draw the form
$status = $formTable->formInfo['status'];
$name = $formTable->formInfo['name'];
$hideform = false;

if( !isset($preview) && $status & ( STATUS_DONE | STATUS_DELETED ) ) {
	$tpl_vars['content']['error'] = _MB_Form_is_not_active;
}
if (!isset($where)) $where="";
if( $where!='tab' && !($status & STATUS_ACTIVE) ) {
	if(!(isset($test) && $test && ($status & STATUS_TEST))) {
		$tpl_vars['content']['error'] = _MB_Form_is_not_active ;
		if ( $formTable->formInfo['uid'] != $xoopsUser->uid() ){
			$hideform = true;
		}
	}
}
if ($_POST['referer'] == $FMXCONFIG['autopub_url'])
    $_POST['referer'] .= "?name=$name";

$msg = '';
$flg ="1";
$action = $_SERVER['PHP_SELF'];

if (!empty($_SERVER['QUERY_STRING'])) $action .= "?" . $_SERVER['QUERY_STRING'];

/*
** Save / Resume User Response
*/
if( !isset($preview) && ($status!=STATUS_DONE && $status!=STATUS_DELETED) ) {
	if( !empty($_POST['submit']) || !empty($_POST['next']) ) {
		if (!is_null($submitted = $fmxSaver->response_submitted($sid))){
			redirect_header($_POST['referer'],2,"Only one time survey");
		}
		/*
		 * Check Required
		 */
		$msg = $fmxSaver->response_check_required($sid,$_page);
		if(empty($msg)) {
			/*
			 * Save Responce
			 */
	        if ( $overWrite || ($FMXCONFIG['auth_response'] && $fmxStatus->auth_get_option('resume')) )
	            $fmxSaver->response_delete($sid, $_rid, $_page,$flg);
            $submitCount = new submitCount(XOOPS_ROOT_PATH.'/uploads');	// 2010.05.25 yoshis
            if ($submitCount) $submitCount->countUp($name);				// 2010.05.25 yoshis
			$_rid = $fmxSaver->response_insert($sid,$_page,$_rid);
			if (empty($_POST['next'])) $fmxSaver->response_commit($_rid);
		}
	}
	if(!empty($_POST['resume']) && $FMXCONFIG['auth_response'] && $fmxStatus->auth_get_option('resume')) {
	    response_delete($sid, $_rid, $_page);
		$_rid = response_insert($sid,$_page,$_rid);
	    if ($action == $FMXCONFIG['autopub_url'])
			$fmxStatus->goto_saved("$action?name=$name");
	    else
	        $fmxStatus->goto_saved($action);
		return;
	}
	//if ($FMXCONFIG['auth_response'] && $fmxStatus->auth_get_option('resume')){
	//    response_import_sec($sid, $_rid, $_page);
	//}
}

/*
** Page navigation
*/
if( empty($msg) ){
	if(!empty($_POST['submit']) ) {
		$fmxMail = new mailSender();
		$fmxMail->response_send_email($sid,$_rid);
		if (!$msg) $msg = _MD_FORMMAKEX_THANKS_ENTRY;
		$redirect = htmlspecialchars($_POST['submit_jumpuri'] , ENT_QUOTES);
		if (!isset($redirect)) $redirect = htmlspecialchars($_POST['referer'] , ENT_QUOTES);
		$msg = $fmxStatus->goto_thankyou($sid,$redirect);
		return;
	}elseif (!empty($_POST['next'])) {
		$_page++;		
	}elseif (!empty($_POST['prev']) && $FMXCONFIG['auth_response'] && $fmxStatus->auth_get_option('navigate')) {
		$_page--;
	}
}
if ($_rid==0){
	$_rid = $formTable->formInfo['response_id'];
}
/*
** Display form body. Call Render with Smarty
*/
$xoopsTpl->assign(
	'formheader', array(
		'action' => $action,
		'submit_jumpuri' => htmlspecialchars($fmxStatus->submit_jumpuri() , ENT_QUOTES),
		'referer' => htmlspecialchars($_POST['referer'] , ENT_QUOTES),
		'userid' => $_userid,
		'sid' => $sid,
		'rid' => $_rid,
		'sec' => $_page
	)
);
if(!isset($tpl_vars)){ 
	$tpl_vars = array(
		'content' => array(), 
		'langs' => array(), 
		'config' => array());
}
if (!isset($preview)){
	$tpl_vars['content']['formtag'] = array('action' => $action, 'name' => 'phpesp_response', 'enctype' => 'multipart/form-data');
	$tpl_vars['content']['hiddens'] = array(
		'submit_jumpuri' => htmlspecialchars($fmxStatus->submit_jumpuri() , ENT_QUOTES),
		'referer' => htmlspecialchars($_POST['referer'] , ENT_QUOTES),
		'userid' => $_userid,
		'sid' => $sid,
		'rid' => $_rid,
		'sec' => $_page
	);
}
if (!$hideform){
	$formTable = new formmakexTable($sid);
	$tpl_vars['content']['form'] = $formTable->formInfo;
	//$xoopsTpl->assign('form',$tpl_vars['content']['form']);
	$tpl_vars['content']['form']['message'] = $msg;
	/*
	 * Form Html Rending with default answer
	 */
	$formRender = new formmakexHtmlRender();
	$postdata=null;
	if (isset($fmxSaver) && isset($fmxSaver->postdata)) $postdata=$fmxSaver->postdata;
	$tpl_vars['content']['questions'] = $formRender->formRender_smarty($sid,$_page,$_rid,$postdata);
	$tpl_vars['content']['sections'] = $formRender->sections;
	$num_sections = $formRender->pages;
	if($formRender->has_required) {
	   	$tpl_vars['content']['form']['has_required']=_MD_ASTERISK_REQUIRED;
	}
	$page_str = 'Page '.number_format($_page+1).' of '.number_format($formRender->pages);
	$tpl_vars['content']['form']['page'] = $formRender->pages > 1 ?  $page_str : '';
	/*
	if($this->pages>1) {
		$xoopsTpl->assign('formPage','Page '.number_format($section+1).' of '.number_format($this->pages));
	} 
	*/
	$tpl_vars['content']['buttons'] = array();
	/*
	if ($FMXCONFIG['auth_response']) {
		if ($fmxStatus->auth_get_option('navigate') && $_page > 1) { 
			$xoopsTpl->assign('auth_response','<input type="submit" name="prev" value="Previous Page">');
			$tpl_vars['content']['buttons']['prev'] = array('label' => 'Previous Page', 'name' => 'prev', 'type' => 'submit');
		}
		if ($fmxStatus->auth_get_option('resume')) {
			$xoopsTpl->assign('auth_response','<input type="submit" name="resume" value="Save">');
			$tpl_vars['content']['buttons']['save'] = array('label' => 'Save', 'name' => 'resume', 'type' => 'submit');
		}
	}
	*/
	if($_page == $num_sections)	{
		$xoopsTpl->assign('formfooter', array('name' =>'submit','value'=>_MD_SUBMIT_FORM));
		$tpl_vars['content']['buttons']['submit'] = array('label' => _MD_SUBMIT_FORM, 'name' => 'submit', 'type' => 'submit');
	} else {
		$xoopsTpl->assign('formfooter', array('name' =>'next','value'=>_MD_NEXT_PAGE));
		$tpl_vars['content']['buttons']['next'] = array('label' => _MD_NEXT_PAGE, 'name' => 'next', 'type' => 'submit');
	}
}
?>
