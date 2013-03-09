<?php
session_start();
  $DB = "MyNippon";// 'a5697723_AIOdb';
  $myCon = mysql_connect(
    'localhost', //'mysql15.000webhost.com',
    'root', //'a5697723_owner',
    'pXo0qr384' //'Apls9534selw'
  );
  if ($myCon == false) die("Connection failed.");
  if (!mysql_select_db($DB, $myCon)) die("Selection failed.");
  mysql_set_charset ( "utf8", $myCon );
?>
