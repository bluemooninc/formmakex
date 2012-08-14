
<?php

$mobileCharset = 'Shift_JIS';
include_once "../../mainfile.php";

require('./conf.php');
include_once('./class/formmakexTable.class.php');
include_once('./class/formmakexHtmlRender.class.php');
if (!defined('ESP_BASE')){
	$dirname = $xoopsModule->getvar("dirname");
	define('ESP_BASE', XOOPS_ROOT_PATH.'/modules/'.$dirname.'/');
}
$CONFIG = ESP_BASE . 'admin/phpESP.ini.php';
require_once($CONFIG);	

$name = isset($_GET['name']) ? strval($_GET['name']) : '';
$sql = "SELECT id FROM ".TABLE_FORM." WHERE name = '$name'";
if ($result = $xoopsDB->query($sql)) {
   	if (!$xoopsDB->getRowsNum($result)){
   		echo 'error';
   		exit;
   	}else{
   		list($sid) = $xoopsDB->fetchRow($result);
   	}
}else{
	echo 'error';
	exit;
}


// ÉwÉbÉ_äÓñ{èÓïÒ
// add header for imode 
if (preg_match("/^DoCoMo\/2.0.*\([^\)]+;W\d+H\d+/",$_SERVER['HTTP_USER_AGENT'],$matches)){
	header("Content-type: application/xhtml+xml; charset=" . $mobileCharset );
	$contentType = "application/xhtml+xml";
	$xmlHeader = '<?xml version="1.0" encoding="'.$mobileCharset.'"?>
<!DOCTYPE html PUBLIC "-//i-mode group (ja)//DTD XHTML i-XHTML(Locale/Ver.=ja/2.0) 1.0//EN" "i-xhtml_4ja_10.dtd">';
}else{
	header("Content-type: text/html; charset=" . $mobileCharset );
	$contentType = "text/html";
	$xmlHeader = '';
}
echo $xmlHeader . '<html><head>';
echo '<meta http-equiv="content-type" content="'.$contentType.'; charset='.$mobileCharset.'" />';
echo "<title>". htmlspecialchars($xoopsConfig['sitename'])."</title></head>\n<body>";
echo "<meta name='HandheldFriendly' content='True' />
      <meta name='PalmComputingPlatform' content='True' />
      </head>
      <body>";

require_once XOOPS_ROOT_PATH.'/class/template.php';
$xoopsTpl = new XoopsTpl();

//$tpl_vars = array('content' => array(), 'config' => array(), 'langs' => array());

include($FMXCONFIG['handler']); // formmakex/public/handler.php

$xoopsOption['template_main'] = 'formmakex_pda.html';
$tpl_vars['config']['mode'] = 'mobile';
$xoopsTpl->assign('formmakex', $tpl_vars);
$xoopsTpl->assign(array(
	'xoops_sitename' => htmlspecialchars($xoopsConfig['sitename']),
	'xoops_slogan' => $xoopsConfig['slogan']
));

echo mb_convert_encoding($xoopsTpl->fetch('db:formmakex_pda.html'), $mobileCharset, 'EUC-JP');

echo '</body></html>';

?>
