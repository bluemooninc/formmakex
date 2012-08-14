<?php
include_once('formmakexResponseLoader.class.php');

class mailSender extends responseLoader{
	/* {{{ proto bool response_send_email(int form_id, int response_id)
	   Retrieves the response from the database and sends an email with the
	   values.  Returns true if mail sent.
	 */
	var $mail_body;
	function response_send_email($sid, $rid,$uname='',$rs_addr='') {
		global $xoopsUser, $xoopsDB, $xoopsConfig;
		global $_POST,$FMXCONFIG;
	
		$debug=0;
		if ($GLOBALS['FMXCONFIG']['allow_email']==0) {
			return true;
		}
		if ($debug) echo sprintf("%u %u %s %s<br />",$sid,$rid,$uname,$rs_addr);
		//
		// Load Form Information
		//
		$sql = "SELECT name,title,info,email,from_option FROM ".TABLE_FORM." WHERE id='${sid}'";
		$result = $xoopsDB->query($sql);
		list($name, $title, $info, $qs_addr, $from_option) = $xoopsDB->fetchRow($result);
		
		if(empty($qs_addr)) return(false);	// $qs_addr = $xoopsConfig['adminmail'];
		if ($debug) echo $sql."<br />";
		//
		// load form questions for mailto option
		//
		$mailto=array();
		$sql = "SELECT * FROM ".TABLE_QUESTION." WHERE form_id='${sid}' AND deleted='N' ORDER BY position,id";
		if($questions_result = $xoopsDB->query($sql)) {
			while($question = $xoopsDB->fetchArray($questions_result)) {
				// process each question
				if ($question['type_id'] == 100) {
					if (eregi("mailto",$question['content'])){
						$mailtoparam = pickup_mail_info($question['content']);
					}
				}elseif ($question['type_id'] == 1) {
					// Yes/No
					if (eregi("mailfromset",$question['name'])){
						$mailoption['mailfromset']=$question['content'];
					}elseif (eregi("to_mailinglist",$question['name'])){
						$mailoption['to_mailinglist']=$question['content'];
					}
				}elseif ($question['type_id'] == 2) {
					if (eregi("mailto",$question['name'])){
						$mailoption['mailto']=$question['content'];
					}elseif (eregi("mailfrom",$question['name'])){
						$mailoption['mailfrom']=$question['content'];
					}elseif (eregi("mailcc",$question['name'])){
						$mailoption['mailcc']=$question['content'];
					}elseif (eregi("mailname",$question['name'])){
						$mailoption['mailname']=$question['content'];
					}elseif (eregi("mailsubject",$question['name'])){
						$mailoption['mailsubject']=$question['content'];
					}
				}elseif ($question['type_id'] == 3) {
					if (eregi("mailbody",$question['name'])){
						$mailoption['mailbody']=$question['content'];
					}
				}
			}
		}
		//lets check to see if user wants human readable email
		if ($GLOBALS['FMXCONFIG']['human_email']) {
			$answers = $this->response_select_human($sid, $rid," ORDER BY q.position");
			$qsep = " : ";
			$isep = ",";
			$end = "\n";
		} else {
			$answers = response_select_compact($sid, $rid);
			$qsep = ' : ';
			$isep = ',';
			$end = "\n";
		}
		if( !$rs_addr && $xoopsUser ){
			$userHander = new XoopsUserHandler($xoopsDB);
			$tUser = $userHander->get($xoopsUser->uid());
			$uname = $tUser->uname();
			$rs_addr =  $tUser->email();
		}elseif(!$uname){
			if ($FMXCONFIG['anonymousname']=="0"){
				$uname = "Anonymous";
			}else{
				$uname = $_POST['userid'];
			}
			$rs_addr = $qs_addr;
		}
		if ($debug){
			echo "<br />Questioner:".$qs_addr;
			echo "<br />uname:".$uname;
			echo "<br />rs_addr: $rs_addr <br />";
		}
		$user = array('form.id' => $sid);
		//'S'.$sid.'R'.$rid.':'. mb_(_MD_MAIL_TITLE) ." $name [$rid]";
		$subject = $title;
		//if ($uname) $subject .= ','.$uname;	
	
		if (formmakexTable::getXoopsModuleConfig('ADD_INFO'))
			$message = $info.$end.$end;
		else
			$message = '';
		reset($user);
		while (list($k, $v) = each($user))
	//		$message .= $k . $qsep . $v . $end;
		$headers = "From: \"phpESP ".
			addslashes($GLOBALS['FMXCONFIG']['version']) .
			"\" <phpesp@". $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'] .">\n";
		$headers .= "X-Sender: <phpesp@". $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'] .">\n";
		$headers .= "X-Mailer: phpESP\n";
		$headers .= "Return-Path: <". $GLOBALS['HTTP_SERVER_VARS']['SERVER_ADMIN'] ."@".
			$GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'] . ">\n";
	
		reset($answers);
	
		$file_charset = formmakexTable::getXoopsModuleConfig('FILE_CHARSET');
		$upurl = XOOPS_URL.$FMXCONFIG['attach_path'];
		$uploads = XOOPS_ROOT_PATH.$FMXCONFIG['attach_path'];
		$i=0;
		$attach=array();
		$blogid='';
		$mailfrom = $xoopsConfig['adminmail'];
		while($arr = array_shift($answers)) {
			unset($x);
			if (count($arr) > 2)
				list($k, $v, $x) = $arr;
			else
				list($k, $v) = $arr;
			if (is_array($v))
				$v = implode($isep, $v);
			if (isset($x)) {
				if (is_array($x))
					$v .= ' (' . implode($isep, $x) . ')';
				else
					$v .= ' = ' . $x;
			}
			//************************************
			// Mailto option controll
			//************************************
			if (isset($mailoption['mailto']  )){ if (eregi($mailoption['mailto'],$k)) $mailtoparam['to']=$v; }
			if (isset($mailoption['mailfrom'])){ if (eregi($mailoption['mailfrom'],$k)) $mailtoparam['from']=$v; }
			if (isset($mailoption['mailcc']))  { if (eregi($mailoption['mailcc'],$k)) $mailtoparam['cc']=$v; }
			if (isset($mailoption['mailname'])){ if (eregi($mailoption['mailname'],$k)) $mailtoparam['name']=$v; }
			if (isset($mailoption['mailsubject'])){ if (eregi($mailoption['mailsubject'],$k)) $mailtoparam['subject']=$v; }
			if (isset($mailoption['mailbody'])){ if (eregi($mailoption['mailbody'],$k)) $mailtoparam['body']=$v;}
			if (isset($mailoption['mailfromset'])){ if (eregi($mailoption['mailfromset'],$k)) $mailtoparam['mailfromset']=$v; }
			if (isset($mailoption['to_mailinglist']  )){ if (eregi($mailoption['to_mailinglist'],$k)) $mailtoparam['to_mailinglist']=$v; }
			//************************************
			// Special controll and Attached files
			//************************************
			if (!eregi("img src=|a href=",$v)){
				// Special Controll Strings
				if (eregi("blogid",$k)){
					$blogid = "b".$v.",";
				}elseif (eregi("email",$k)){
					$mailfrom = $v;
					$message .= $k . $qsep . $v . $end;
				}else{
					$message .= $k . $qsep . $v . $end;
				}
			} else {
				// Attached Files
				$u = split("[<,>]",$v);
				$url = ereg_replace("img src=|a href=","",$u[1]);
				$url = ereg_replace("target='_blank'","",$url);
				$url = ereg_replace("'","",$url);
				$url = $uploads.ereg_replace($upurl,"",$url);
				$url = cnv_mbstr(rawurldecode($url),$file_charset);
				$fnm = $u[2];
				$attach[$i]['url']=$url;
				$attach[$i]['fnm']=$fnm;
				$i++;
			}
		}
		//return(mail($qs_addr,$subject,$message,$headers));
		switch( $from_option ){
			case 0:	// FormAddress to questioner
				$mailfrom = formmakexTable::getXoopsModuleConfig('MAILADDR');
				if (!$mailfrom) $mailfrom = $xoopsConfig['adminmail'];
				$mailto = $qs_addr; break;
				//$xoopsConfig['adminmail']
			case 1:	// Respondent to questioner
				$mailfrom = $rs_addr; $mailto = $qs_addr; break;
			case 2:	// From address in Questionnaire
				$mailto = $qs_addr; break;
			/* No longer option with allow_email
			case 2:
				$ret = send_email_with_attach($rs_addr,$qs_addr,$subject,$message,$headers,$attach); // Respondent to questioner
				$mailfrom = $qs_addr; $mailto = $rs_addr; break;
				break;			
			case 3:	// questioner to questioner
				$mailfrom = $qs_addr; $mailto = $qs_addr; break;
			case 4:	// questioner to Admin
				$mailfrom = $qs_addr; $mailto = $xoopsConfig['adminmail']; break;
			case 5:	// questioner to Special
				$mailfrom = $qs_addr; $mailto = $GLOBALS['FMXCONFIG']['special_addr']; 
				$subject = $GLOBALS['FMXCONFIG']['special_char'].$subject; break;
			*/
		}
		//
		// Send to mailto info in section text. If fill in $mailto.
		//
		//print_r($mailtoparam);
		if (isset($mailtoparam['to']) && isset($mailtoparam['subject']) && isset($mailtoparam['body'])){
			$from = $cc = $bcc = "";
			if ( isset($mailtoparam['mailfromset']) && isset($mailtoparam['from'] ) ){
				if(function_exists('mberegi')){
					if ( mberegi($mailtoparam['mailfromset'],_MB_Yes) ) $from=$mailtoparam['from'];
				}else{
					if ( preg_match($mailtoparam['mailfromset'],_MB_Yes) ) $from=$mailtoparam['from'];
				}
			}
			if (isset($mailtoparam['name'])) $mailtoparam['body'].="\n".$mailtoparam['name'];
			if (isset($mailtoparam['cc'])) $cc = $mailtoparam['cc'];
			if (isset($mailtoparam['bcc'])) $bcc = $mailtoparam['bcc'];
			//echo $from . $mailtoparam['to'] . $mailtoparam['subject']."<br>";
			send_email_with_attach($from,$mailtoparam['to'],$mailtoparam['subject'],$mailtoparam['body'],$headers,$attach,$cc,$bcc);
		}
		if (!isset($mailfrom) && isset($mailtoparam['from'])) $mailfrom=$mailtoparam['from'];
		//
		// For Mailling List
		//
		if (isset($mailoption['to_mailinglist'])){ 
			$message = $mailfrom;	// Change Message to Mail address for mailing list
			// Deny to mail sending
			if(function_exists('mberegi')){
				if ( mberegi($mailtoparam['to_mailinglist'],_MB_No) ) $mailto = '';
			}else{
				if ( preg_match($mailtoparam['to_mailinglist'],_MB_No) )  $mailto = '';
			}
		}
		if ($debug) print(" from = $mailfrom,to = $mailto");
		//echo $mailfrom.$mailto.$blogid.$subject."<br>";
		return $this->send_email_with_attach($mailfrom,$mailto,$blogid.$subject,$message,$headers,$attach);
	}
	/* }}} */
	function send_email_with_attach($from,$to,$subject,$message,$headers,$attach,$cc='',$bcc='') {
		global $xoopsConfig;
	
		if (!$to) return;
		$mail_charset = $GLOBALS['FMXCONFIG']['mail_charset'];
		if (!$from) $from = formmakexTable::getXoopsModuleConfig('MAILADDR');
		if (!$from) $from = $xoopsConfig['adminmail'];
		$attach_flag = FALSE;
		$mpstr = '';
		/*
		$boundary = "----------". uniqid( rand() );
		foreach ($attach as $att) {
			$fnm=$att['fnm'];
			$url=$att['url'];
			$mpstr .= make_multipart($url,$fnm,$boundary,$mail_charset);
			$attach_flag = TRUE;
		}
		$mimetype = ($attach_flag ? "multipart/mixed;\n\tboundary=\"$boundary\"" : "text/plane;charset=\"".$mail_charset."\"" );
		$chgheader = 
			"From: ". $from ."\n" . 
			"Reply-To: ". $from . "\n" . 
			"X-Mailer: PHP/".phpversion()."\n" . 
			"Return-Path: <". $from . ">\n" . 
			"MIME-Version: 1.0\n" . 
			"Content-Type: " . $mimetype . "\n";
		$chgheader .= $cc ? "cc:" . $cc . "\n" : "";
		$chgheader .= $bcc ? "bcc:" . $bcc . "\n" : "";
		$chgheader .= $attach_flag ? "Status:   \n\nThis is multipart message.\n\n" : "";
		if( $attach_flag ) {
			$message = "--" .$boundary . "\n".
				"Content-Type: text/plain; charset = \"".$mail_charset."\"\n".
				"Content-Transfer-Encoding: 7bit\n\n".
				$message."\n\n".$mpstr;
		}
		*/
		//if ( function_exists('mb_encode_mimeheader') ) $subject = mb_encode_mimeheader( $subject, $mail_charset, "B" );
		$message = $this->cnv_mbstr( $message, $mail_charset);
		$message = preg_replace("/\x0D\x0A|\x0D|\x0A/","\n",$message);
		//ini_set("sendmail_name", $from);
		//if (extension_loaded('mbstring'))
		//	return mb_send_mail($to, $subject, $message, $chgheader, "-f ".$from);
		//else
		//return mail($to, $subject, $message, $chgheader, "-f ".$from); //Will not work PHP safe mode
		//return mail($to, $subject, $message, $chgheader);
		$xoopsMailer =& getMailer();
		$xoopsMailer->useMail();
		$xoopsMailer->setToEmails($to);
		$xoopsMailer->setFromEmail($from);
		$xoopsMailer->setFromName($xoopsConfig['sitename']);
		$xoopsMailer->setSubject($subject);
		$xoopsMailer->setBody($message);
		for($i=0;$i<count($attach);$i++) {
			$res = $xoopsMailer->multimailer->AddAttachment($attach[$i]['url'],$attach[$i]['fnm'],"base64",fnm2mime($attach[$i]['fnm']));
			echo $attach[$i]['fnm'] . " Source Attached(".(($res)?ok:error).")<br />";
		}
		if ( $cc ) $xoopsMailer->AddCC($cc);
		if ( $bcc ) $xoopsMailer->AddBCC($bcc);

		$this->mail_body = $message;
		
		if ( !$xoopsMailer->send() )
			echo "Send failed to " . $to;
		//else echo "Sent to " . $to;
	}
}
?>