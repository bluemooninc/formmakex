<?php
// $Id: doc_updateinfo.php,v 1.1.1.1 2005/08/10 12:14:04 yoshis Exp $

include '../../../../mainfile.php';

include '../../../../include/cp_functions.php';

	xoops_cp_header();
?>
<h1>バージョンアップについて</h1>
この作業を行うにはバックアップ・リストアツールが必要です。 <p>
<li><a href=http://www.bluemooninc.biz/~xoops2/modules/mydownloads/singlefile.php?cid=3&lid=36">BackPack</a> は Bluemooninc.biz　で入手できます。<p>
<li><a href=http://www.phpmyadmin.net/home_page/index.php">phpMyAdmin</a> は The phpMyAdmin Project　で入手できます。<p>
<hr>

<h2>V0.3からV0.4へのアップデート</h2>

<h3>1. formmakex_formテーブルをバックアップ</h3>
<p>現在使用している formmakex_formテーブルをバックアップしてください。 ('xoops_'はXOOPS接頭語です。) 

<h3>2. バックアップファイルの編集</h3>
<p><li>Text Editorでバックアップファイルを開いてください。
<p><li>formmakex_formテーブルの定義に対して'changed'の１つ下の行に以下の定義を加えてください。
<p>&nbsp;&nbsp; response_id	INT UNSIGNED NOT NULL,
<p><li>それを保存してください。 

<h3>3. リストア</h3>
<p>編集されたバックアップファイルをリストアしてください。
<p>phpMyAdminを使用してリストする場合は、目的のテーブルをドロップしてリストアください。
<hr>

<h2>V0.2XからV0.3へのアップデート</h2>

<h3>1. BMEF テーブルをバックアップ</h3>
<p>現在使用している xoops_bmef_*テーブルの全てをバックアップしてください。 ('xoops_'はXOOPS接頭語です。) 

<h3>2. バックアップファイルの編集</h3>
<p><li>Text Editorでバックアップファイルを開いてください。
<p><li>bmef_respondentテーブルのすべての構造を置き換えてください。 
<p>&nbsp;&nbsp; formmakex/sql/mysql.sqlの上のformmakex_respondentテーブルからすべての構造をコピーしてください、そして、それを置き換えてください。
<p><li>bmef_formテーブルを探し、'mail'の１つ下の行に以下の定義を加えてください。
<p>&nbsp;&nbsp; from_option TINYINT( 3 ) UNSIGNED DEFAULT'0'NOT NULL
<p><li>すべてのテーブル名称を'bmef_'から'formmakex_'へ置き換えてください。
<p><li>それを保存してください。 

<h3>3. リストア</h3>
<p>編集されたバックアップファイルをリストアしてください。
<p>phpMyAdminを使用してリストする場合は、formmakexの全てのテーブルをドロップしてリストアください。
<hr>

<?php
	xoops_cp_footer();
?>
