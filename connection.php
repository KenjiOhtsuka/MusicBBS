<?php
session_start();
  $DB = "dbnamen";
  $myCon = mysql_connect(
    'localhost',
    'root', 
    'password'
  );
  if ($myCon == false) die("Connection failed.");
  if (!mysql_select_db($DB, $myCon)) die("Selection failed.");
  mysql_set_charset ( "utf8", $myCon );
?>
