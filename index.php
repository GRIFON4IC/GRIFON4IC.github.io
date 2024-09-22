<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Script Name: Chesser FileHosting Uploader
 * Description: The script allows upload and manage uploaded files on a hosting server
 *
 * How to use:  1) place this file to some $webdir folder on your hosting and set this variable
 *              2) create some folder where you files will be uploaded and stored, set $uploaddir variable to the according value
 *              3) change the created folder permissions to 777
 *              4) change the $password variable to desired. You should input it all times when you are managing the files
 *              5) open this script from browser, type the password, select a desired file for uploading and press the button
 *
 * @author  Chesser <chesser@chesser.ru>
 * @copyright 2004-2011 Chesser, http://chesser.ru
 * @version 0.1 2011-03-12
 * @link  http://chesser.ru/blog/private-file-hosting-script/
 */

/**
 * if the script exec from http://chesser.ru/chesser-uploader.php 
 * then set $webdir to http://chesser.ru/
 * if from http://chesser.ru/file_hosting/chesser-uploader.php
 * then set $webdir to http://chesser.ru/file_hosting/
 * slash at the end is neccessary
 */
$webdir    = 'http://chesser.ru/file_hosting/';

/**
 * if $uploaddir is myfiles/ and $webdir is http://chesser.ru/
 * then all your files will be uploaded to http://chesser.ru/myfiles/
 * if $webdir is http://chesser.ru/file_hosting/
 * then all your files will be uploaded to http://chesser.ru/file_hosting/myfiles/
 * slash at the end is neccessary
 */
$uploaddir = 'myfiles/';

$password  = 'password';

if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pass'] != $password)
    die('Bad password');

$filepath = $uploaddir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filepath))
    $link = "<a href=\"$webdir$filepath\">$webdir$filepath</a>";

header('Content-Type: text/html; charset=UTF-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Chesser FileHosting Uploader</title>
<style type="text/css">
body {
  font-family: tahoma, verdana, arial, sans-serif;
  color:#343434;
}
</style>
</head>
<body>
<div style="text-align: center;">
Chesser FileHosting Uploader
&copy;
<a href="http://chesser.ru">Chesser.Ru</a>
<br/>
<br/>
<br/>
<br/>
<form enctype="multipart/form-data" method="post">
Password: <input name="pass" size="10" value="<?php echo $_POST['pass'] ?>" />
<br/>
<br/>
Select file: <input name="userfile" type="file" /><br/>
<br/>
<br/>
<input type="submit" value="Upload/Show files" /><br/>
<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pass'] == $password): ?>
<?php
    echo $link;
    if(!empty($_POST['to_delete'])) {
        foreach($_POST['to_delete'] as $file)
            @unlink($file);
    }
    $files = glob( $uploaddir . '*', GLOB_MARK );
    if(!empty($files)):
?>
<br/>
<br/>
<hr/>
<table cellpadding="5" style="font-size: 0.8em;">
<tr>
  <th>Del</th>
  <th>Filename</th>
  <th>Size</th>
  <th>Date</th>
</tr>
<?php
        foreach($files as $filepath):
?>
  <tr>
    <td align="center"><input type="checkbox" name="to_delete[]" value="<?php echo $filepath?>" /></td>
    <td align="left"><?php echo "<a href=\"$webdir$filepath\">$webdir$filepath</a>"?></td>
    <td align="right"><?php echo filesize($filepath)?></td>
    <td align="right"><?php echo date('Y-m-d H:i:s', filemtime( $filepath ) )?></td>
  </tr>
<?php   endForeach ?>
</table>
<input type="submit" value="delete selected">
<?php endIf ?>
<?php endIf ?>
</form>
</div>
</body>
<html>
