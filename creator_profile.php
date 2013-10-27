<?php
  require_once('constants.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title><?php echo ConstText::BBStitle; ?></title>
<?php
//  include('meta.php');
  include('headerScript.php');
  include('css.php');
?>
</head>
<body>
<h1><?php echo ConstText::BBStitle; ?></h1>
<?php
  include('headerPanel.php');
?>


<?php
  $title = "作者プロフィール";
  $message = "楽譜の表示できる掲示板がないな〜と思って作ってみました。<br /><br />";
  echo createIntroductionHtml($title, 'escamilloIII', '16198161', '', 'black', $message);
?>

