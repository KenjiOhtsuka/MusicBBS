<?php
session_start();

require_once('constants.php');

$taskId = mt_rand();
$_SESSION['taskId'] = $taskId;
$taskId = md5($taskId);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $writer = htmlspecialchars($_POST["writer"]);
  $title = htmlspecialchars($_POST["title"]);
  $message = htmlspecialchars($_POST["message"], ENT_QUOTES);
  //$message = mysql_real_escape_string($message);
  $message = nl2br($message);
  //$message = mb_eregi_replace('/[[(\r\n)\r\n](<br\s/>)]/g', ' <br /> ', $message);
  $twitter_id = htmlspecialchars($_POST["twitterID"]);
  $mixi_id = htmlspecialchars($_POST["mixiID"]);
  $facebook_id = htmlspecialchars($_POST["facebookID"]);
  $color = htmlspecialchars($_POST["color"]);
  //$password = htmlspecialchars($_POST["password"]);
  $topic_id = htmlspecialchars($_POST["topic_id"]);
  $post_id = htmlspecialchars($_POST["post_id"]);

  $button_text = '';
  if ($_POST['mode'] != ModeType::Edit) {
    $button_text = '投稿する';
    $header_message = '<p>投稿します。よろしいですか？</p>';
  } else {
    $button_text = '更新する';
    $header_message = '<p>更新します。よろしいですか？</p>';
  } 
echo <<<EOT
  {$header_message}
  <form action="{$_SERVER['PHP_SELF']}" onsubmit="return formOnPost();" method="POST" name="ConfirmDialog" id="ConfirmDialog">
    <input type="submit" value="{$button_text}" />
    <input type="reset" value="キャンセル" onclick="cancelOnClick();" />
    <input type="hidden" value="{$taskId}" name="preTaskId" id="preTaskId" />
  </form>
EOT;
}
?>
