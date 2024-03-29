<?PHP
//  ------------------------------------------------------------------------ //
//                Bluemoon.Multi-Form                                      //
//                    Copyright (c) 2006 Yoshi.Sakai @ Bluemoon inc.         //
//                       <http://www.bluemooninc.biz/>                       //
// ------------------------------------------------------------------------- //
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
/******************************************************************************
XOOPS Header
******************************************************************************/
require('../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
/******************************************************************************
$_SESSION変数よりサーベイの必要データを切り出します。......> qid を適宜変更ください。
******************************************************************************/
// qidの探し方例＞
//<td class="even" align="left" >�〔松�</td>
//<td class="odd" align="left"><input type="text" size="30" name="31" />
//入力された名称を拾いたい場合は、アンケートのソースを表示させて、文字列「�〔松痢廚鮹気靴修猟掌紊砲△�name="31"の数字部分がqidです。
//
$sids = array();									// サーベイIDの収集先を確保
foreach($_SESSION['formmakex'] as $key => $val) {	// SESSION変数を展開
	if (!in_array($val['sid'], $sids))				// 収集済みサーベイID以外なら
		$sids[]=$val['sid'];						// 回答済みサーベイのID配列へ追加
	if ($val['qid'] == 31)							// 入力値を拾いたい質問をqidで指定し......> 任意のqid値へ変更ください。
		$inputname = $val['val'];					// その値を変数へ代入する
	if ($val['qid'] == 142)							// 質問分岐する Multiple Choice の qid......> 任意のqid値へ変更ください。
		$choiced_menu = explode("|",$val['val']);	// 選択項目のChoice ID (question_choiceテーブル参照)を配列に格納
}
/******************************************************************************
メニュー文字列の定義......> cid,sid,title,url を適宜変更ください。
******************************************************************************/
// Usage :
//   'cid' : マルチチョイスで選択される項目のIDです。分岐元アンケートのソースで該当箇所を検索するかformmakex_question_choiceテーブルより検索ください。
//           例＞<input type="checkbox" name="142[]" value="384" />質問内容文字列.... この場合は、qid=142 cid=384 という事になります。
//   'sid' : サーベイIDです。サーベイ管理の一覧の左端のナンバーで指定します。テーブルでは、formmakex_formより検索できます。
// 'title' : サブメニューに表示するアンケートのタイトル文字列です。HTMLタグを入れれば装飾できます。
//   'url' : サーベイのURLを指定します。
$form_url = XOOPS_URL."/modules/'.$mydirname.'/webform.php?name=";
$menus = array(
array('cid'=>"384", 'sid'=>"3",  'title'=>"1. 質問１でＡと答えた方への追加質問", 'url'=>$form_url . "detail_a"),
array('cid'=>"385", 'sid'=>"4",  'title'=>"2. 質問１でＢと答えた方への追加質問", 'url'=>$form_url . "detail_b"),
array('cid'=>"386", 'sid'=>"5",  'title'=>"3. 質問１でＣと答えた方への追加質問", 'url'=>$form_url . "detail_c"),
);
/******************************************************************************
以下、取得した変数を利用してメッセージやメニューの動作を処理します。
******************************************************************************/
echo "<H2>".$inputname."さん、アンケート回答有難うございます。引き続き該当する項目のアンケートにお答えください。</H2>";
if (in_array( $menus[ 0]['cid'], $choiced_menu))
	if (in_array( $menus[ 0]['sid'], $sids)) echo $menus[ 0]['title'] . '<FONT color="red">...回答済み</FONT><BR />';
	else echo '<A HREF="'. $menus[ 0]['url'].'">'. $menus[ 0]['title'].'</A><BR />';
if (in_array( $menus[ 1]['cid'], $choiced_menu))
	if (in_array( $menus[ 1]['sid'], $sids)) echo $menus[ 1]['title'] . '<FONT color="red">...回答済み</FONT><BR />';
	else echo '<A HREF="'. $menus[ 1]['url'].'">'. $menus[ 1]['title'].'</A><BR />';
if (in_array( $menus[ 2]['cid'], $choiced_menu))
	if (in_array( $menus[ 2]['sid'], $sids)) echo $menus[ 2]['title'] . '<FONT color="red">...回答済み</FONT><BR />';
	else echo '<A HREF="'. $menus[ 2]['url'].'">'. $menus[ 2]['title'].'</A><BR />';
/******************************************************************************
XOOPS footer
******************************************************************************/
include(XOOPS_ROOT_PATH.'/footer.php');
?>
