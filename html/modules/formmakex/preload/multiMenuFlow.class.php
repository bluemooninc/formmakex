<?php
if (!defined('XOOPS_ROOT_PATH')) exit();

//require_once XOOPS_MODULE_PATH.'/profile/class/FieldType.class.php';

class formmakex_multiMenuFlow extends XCube_ActionFilter
{
	var $snoopData = array(
		'minutes'=>0
	);
	/**
	 * @public
	 */
	function postFilter(){
		$module_handler = xoops_gethandler( 'module' );
		$module = $module_handler->getByDirname("formmakex");
		$mid = $module->mid();
		if (isset($_SESSION['multiMenuFlow'][$mid]) ){
			$this->convSubmit($_SESSION['multiMenuFlow'][$mid]);
		}
		//var_dump($_SESSION['multiMenuFlow'][$mid]); die;
	}
	function convSubmit(&$var) {
		global $xoopsUser,$xoopsDB;
	
		$sid = $var['sid'];
		$sql = "SELECT * FROM ".$xoopsDB->prefix("formmakex_question")
			." WHERE form_id = $sid ORDER BY position,id";
		$result = $xoopsDB->query($sql);
		
		if(!$result) return(false);
		while($question = $xoopsDB->fetchArray($result)) {
			$qname = "Q" . $question['id'];
			if (isset($var[$qname])){
				$ret[] = array(
					'position'	=> $question['position'],
					'name'		=> $question['name'],
					'title'		=> $question['content'],
					'type_id'	=> $question['type_id'],
					'input' 	=> $this->getContent($question['type_id'],$var[$qname])
				);
			}
		}
		if(isset($this->snoopData)){  
			$ret[] = array(
				'position'	=> NULL,
				'name'		=> 'minutes',
				'title'		=> 'minutes',
				'type_id'	=> NULL,
				'input' 	=> $this->snoopData['minutes']
			);
		}
		$var['formmakex'] = $ret;
	}
	
	function getContent($type_id,$var) {
		global $xoopsDB;
		switch($type_id) {
			case '1':	// Yes/No
				break;
			case '2':	// single text line
				break;
			case '3':	// essay
				break;
			case '4':	// radio
				$sql = "SELECT content FROM " . $xoopsDB->prefix("formmakex_question_choice") . " WHERE id = ".$var;
				$result = $xoopsDB->query($sql);
				list($content) = $xoopsDB->fetchRow($result);
				$this->snoopValue($content);
				break;
			case '5':	// check boxes
				$sql = "SELECT content FROM " . $xoopsDB->prefix("formmakex_question_choice") . " WHERE id in ( ". implode(",",$var) . " )";
				$result = $xoopsDB->query($sql);
				while (list($v) = $xoopsDB->fetchRow($result)){
					$this->snoopValue($v);
					$content[] = $v;
				}
				break;
			case '6':	// dropdown box
				break;
			case '7':	// rating
				break;
			case '8':	// ranking
				break;
			case '9':	// date
				break;
			case '10':	// numeric
				break;
			case '40':	// Attache
				break;
			case '99':	// Page Break
				break;
			case '100':	// Section Text
				break;
		}
		return $content;
	}
	function snoopValue($var){
		$params = explode(" ",$var);
		foreach($params as $v){
			//if ( preg_match("/(&yen;)([0-9,]+)/u",$v,$matches) ){}
			if ( preg_match("/(.*)(分)/u",$v,$matches) ){
				$ret = $matches[1];
			}
		}
		$this->snoopData['minutes'] += $ret;
		return $ret;
	}
	
}
?>