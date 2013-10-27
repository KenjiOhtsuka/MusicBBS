<?php
  require_once('constants.php');
?>
<!--<\?xml version="1.0" encoding="UTF-8" \?>-->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>説明 - <?php echo ConstText::BBStitle; ?></title>
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
  $title = "説明";
  $message = "この掲示板では ABC記法 を利用して楽譜を書くことができます。";
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
  
  $title = 'ABC記法 とは';
  $message = "テキストで音楽を描くための方法。欧米ではABC記法による膨大な楽曲のデータがあったりする。";
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
  
  $title = 'ABC記法 の書き方';
  $message = '<p>こちらではまだ資料が用意できておりません。大変申し訳ありませんが、下記URLをご参考になさってください。</p>';
  $message .= '<ul>
  <li><a href="http://abcnotation.com/" target="_blank">abcnotation.com</a> <a href="http://abcnotation.com/examples" target="_blank">Samples</a></li>
  <li><a href="http://trillian.mit.edu/~jc/music/abc/doc/ABCtutorial.html" target="_blank">チュートリアル</a></li>
</ul>';
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
?>
