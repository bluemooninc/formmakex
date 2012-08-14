<?php
class questionLoader{
	var $rows = array();
	var $num_sections = 1;
	var $totalRow;
	var $questions_option;
	var $has_choices;
	
	function questionLoader($sid){
		global $xoopsDB;

		// build array of question IDs
		$sql = "SELECT * FROM ".TABLE_QUESTION." WHERE form_id='$sid' AND deleted='N' AND type_id != 99 ORDER BY position";
		$result = $xoopsDB->query($sql);
		$this->totalRow = $xoopsDB->getRowsNum($result);
		while($myrow = $xoopsDB->fetcharray($result)){
			$this->rows[]=$myrow;	//$myrow['id'] 
			if ( $myrow['type_id']=="99" && $myrow['deleted']=="N") $$this->num_sections++; 
		}
	}
	function is_rows(){
		return count($this->rows);
	}
	/* {{{ proto array esp_type_has_choices()
   Returns an associative array of bools indicating if each
   question type has answer choices. */
	function esp_type_has_choices() {
		global $xoopsDB;
		$has_choices = array();
		$sql = "SELECT id, has_choices FROM ".TABLE_QUESTION_TYPE." ORDER BY id";
		$result = $xoopsDB->query($sql);
		while(list($tid,$answ) = $xoopsDB->fetchRow($result)) {
			if($answ == 'Y')
				$has_choices[$tid]=1;
			else
				$has_choices[$tid]=0;
		}
		
		return($has_choices);
	}
	function questionHasChoices($type_id){
		global $xoopsDB;
		$sql = "SELECT has_choices FROM ".TABLE_QUESTION_TYPE." WHERE id='" . $type_id ."'";
		list($this->has_choices) = $xoopsDB->fetchRow($xoopsDB->query($sql));
	}
	function questionChoice($updated,$curr_qNumber,$edit_qid){
		global $xoopsDB;
		if ($edit_qid) {
			$sql = "SELECT id,content FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${edit_qid}' ORDER BY id";
			$result = $xoopsDB->query($sql);
			$c = $xoopsDB->getRowsNum($result);
		} else {
			if (isset($_POST['num_choices']))
				$c = intval($_POST['num_choices']);
			else
				$c = $GLOBALS['FMXCONFIG']['default_num_choices'];
		}
		if(isset($_POST['extra_choices']))
			$num_choices = max($c, $_POST['num_choices']) + 1;
		else
			$num_choices = $c;
		$this->questions_option['num_choices'] = $num_choices;
		$this->questions_option['choices'] = array();
		for($i=1; $i<$num_choices+1; ++$i) {
			if ($edit_qid) {
				list($choice_id, $choice_content) = $xoopsDB->fetchRow($result);
			} else {
				if ( $curr_qNumber && isset($_POST["choice_id_$i"]) ) {
					$choice_id = intval($_POST["choice_id_$i"]);
				}else{
					$choice_id = 0;
				}
				if(isset($_POST["choice_content_$i"]))
				$choice_content = htmlspecialchars($_POST["choice_content_$i"],ENT_QUOTES);
			}
			$this->questions_option['choices'][]=array(
				"numbered" => $i,
				"choice_id" => $choice_id,
				"name" => "choice_content_".$i,
				"value" => isset($choice_content) ? $choice_content : ''
			);
		}
		//var_dump($_POST); die;
	}
	function cnv_mbstr($str, $encto) {
	// _CHARSET is XOOPS defined char setting
		if (extension_loaded('mbstring')){
			return  mb_convert_encoding($str,$encto,_CHARSET);
		} else {
			return $str;
		}
	}
}
?>