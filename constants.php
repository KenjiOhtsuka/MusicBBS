<?php
class PageType {
  const Unknown = 0;
  const Board = 1;
  const Topic = 2;
  const Comment = 3;
  const Summery = 4;
  const Search = 5;
}
class InputType {
  const None = 0;
  const Topic = 1;
  const TopicReply = 2;
  const CommentReply = 3;
  const Edit = 4;
}
class ModeType{
  const View = 0;
  const Edit = 1;
}
class PostType {
  const Topic = 0;
  const Reply = 1;
  const Edit = 2;
}
class ConstParam {
  const BoardTopicCount = 3;
  const BoardTopicCommentCount = 2;
  const TopicCommentCount = 5;
  const SummeryCount = 30;
  const Pager = 20;
}
class GetParam {
  const TopicId = 'topic_id';
  const PostId = 'id';
  const SummeryNumber = 'summery_number';
  const PageNumber = 'page_number';
  const Mode = 'mode';
}
class ConstText {
  const BBStitle = '楽譜の書ける音楽掲示板';
  const PasswordSalt = 'gd6GtpsO2hnyDVNUkungsdwL15';
  const Keywords = '楽譜,音楽,掲示板,教育,学習,練習,ABC記法';
  const Description = '楽譜の表示できる音楽掲示板。ABC記法を使って、楽譜を表示することができます。ぜひご活用ください。';
  const URLPrefix = 'http://nippon.vacau.com/TeXBBS/';
}

function metaTags($title) {
  $code = '
  <meta charset="utf-8" />
  <meta name="description" content="'.ConstText::Description'" />';
  if (isset($title)) {
    $code .= '
  <meta name="keywords" content="'.$title.','.ConstText::Keywords.'" />';
  } else {
    $code .= '
  <meta name="keywords" content="'.ConstText::Keywords.'" />';
  }
  return $code;
}

function createPager($page_number, $item_count, $items_a_page, $pagers_a_page, $html_param) {
  $pager_count = ceil($item_count / $items_a_page);
  $start_pager = (ceil($page_number / $pagers_a_page) - 1) * $pagers_a_page + 1;
  $end_pager = min(($start_pager + $pagers_a_page - 1), $pager_count);

  $html = '<div class="pager">';
  if ($start_pager > 1) {
    $html = "<a href=\"{$html_param}=".(string)($start_page - 1)."\">&lt;&lt;</a> ";
  }
  for ($i = $start_pager; $i <= $end_pager; $i++) {
    if ($i == $page_number) {
      $html .= "<span style=\"font-size:large;\">{$i}</span> ";
    } else {
      $html .= "<a href=\"?{$html_param}={$i}\">{$i}</a> ";
    }
  }
  if ($end_pager < $pager_count) {
    $html .= "<a href=\"{$html_param}=".(string)($end_page + 1)."\">&gt;&gt;</a> ";
  }
  $html .= "</div>";
  return $html;
}

function createSocialLink($twitter_id = '', $mixi_id = '', $facebook_id = '', $title, $topic_id, $post_id = 0) {
  $socialLink = '';
  if (!empty($twitter_id)) {
    if (!empty($topic_id)) {
      if (!empty($post_id)) {
        $url = ConstText::URLPrefix."index.php?".GetParam::TopicId."={$topic_id}&".GetParam::PostId."={$post_id}";
      } else {
        $url = ConstText::URLPrefix."index.php?".GetParam::TopicId."={$topic_id}";
      }
    } else {
      $url = ConstText::URLPrefix."index.php";
    }
    $url = htmlspecialchars($url);
    $title = htmlspecialchars($title);
    $twitter_id = htmlspecialchars($twitter_id);
    $socialLink .= "<a href=\"http://twitter.com/share?text={$title}&url={$url}&via={$twitter_id}\">Twitter</a> ";
  }
  if (!empty($mixi_id)) {
    $socialLink .= "<a href=\"http://mixi.jp/show_friend.pl?id={$mixi_id}\" target=\"_blank\">mixi</a> ";
  }
  if (!empty($facebook_id)) {
    $socialLink .= "<a href=\"http://facebook.com/{$facebook_id}\" target=\"_blank\">facebook</a> ";
  }
  return $socialLink;
}

function createCommentHtml($topic_id, $post_id, $title, $writer, $twitter_id, $mixi_id, $facebook_id, $color = 'black', $message, $created, $modified) {
  if (!empty($modified)) {
    $datetime = $modified;
  } else {
    $datetime = $created;
  }
  $html = "  <div class=\"comment\">";
  $html .= "    <div>CommentNo. {$post_id} <div style=\"float:right;\">{$datetime}</div></div>\n";
  $html .= "    <h3><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&id={$post_id}\">{$title}</a></h3>\n";
  $html .= "    By {$writer}\n";
  $html .= "    <div style=\"float:right;\">\n      ";
  $html .= createSocialLink($twitter_id, $mixi_id, $facebook_id, $title, $topic_id, $post_id);
  $html .= "\n    </div>\n";
  $html .= "    <hr />\n";
  $html .= "    <div class=\"message\" style=\"color:{$color};\">{$message}</div>\n";
  $html .= "    <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::PostId."={$post_id}&".GetParam::Mode."=".ModeType::Edit."\">編集</a>　<a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::PostId."={$post_id}\">コメント</a></div>\n";
  $html .= "  </div>\n";
  return $html;
}
function createTopicHtml($topic_id, $title, $writer, $twitter_id, $mixi_id, $facebook_id, $color = 'black', $message, $created, $modified) {
  if (!empty($modified)) {
    $datetime = $modified;
  } else {
    $datetime = $created;
  }
  $html = "<div >";
  $html .= "  <div>No. {$topic_id} <div style=\"float:right;\">{$datetime}</div></div>\n";
  $html .= "  <h2><a href=\"index.php?topic_id={$topic_id}\">{$title}</a></h2>\n";
  $html .= "  By {$writer}\n";
  $html .= "  <div style=\"float:right;\">\n    ";
  $html .= createSocialLink($twitter_id, $mixi_id, $facebook_id, $title, $topic_id);
  $html .= "\n  </div>\n";
  $html .= "  <hr />\n";
  $html .= "  <div class=\"message\" style=\"color:{$color};\">{$message}</div>\n";
  $html .= "  <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::Mode."=".ModeType::Edit."\">編集</a>　<a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}\">コメント</a></div>\n";
//  $html .= "  <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}\">コメント</a></div>\n";
  $html .= "</div>\n";
  return $html;
}
function createIntroductionHtml($title, $twitter_id, $mixi_id, $facebook_id, $color = 'black', $message) {
  $html = "<div class=\"topic\">";
  $html .= "  <h2><a href=\"training.php\">{$title}</a></h2>\n";
  $html .= "  <div style=\"text-align:right;\">\n      ";
  $html .= createSocialLink($twitter_id, $mixi_id, $facebook_id, ConstText::BBStitle, '');
  $html .= "\n  </div>\n";
  $html .= "  <hr />\n";
  $html .= "  <div class=\"message\" style=\"color:{$color};\">{$message}</div>\n";
  $html .= "</div>\n";
  return $html;
}

function outputForm($formAction, $title = '', $writer = '', $message = '', $color = '',
                   $mixi_id = '', $twitter_id = '', $facebook_id = '', 
                   $topic_id = '', $post_id = '', $mode = ModeType::View) {
  $blue_color = '';
  $red_color = '';
  $green_color = '';
  switch ($color) {
    case 'blue':
      $blue_color = ' selected="selected"';    
      break;
    case 'red':
      $red_color = ' selected="selected"';
      break;
    case 'green':
      $green_color = ' selected="selected"';
      break;
    default:
      break;
  }
  $html = "
  <div id=\"inputArea\">
  <form action=\"{$formAction}\" method=\"POST\" name=\"MathPad\" id=\"MathPad\">
  <table>
  <tbody>
    <tr>
      <td>タイトル</td>
      <td colspan=\"4\"><input type=\"text\" id=\"title\" name=\"title\" maxlength=\"30\" value=\"{$title}\" required=\"required\" /></td>
    </tr>
    <tr>
      <td>名前</td>
      <td colspan=\"4\"><input type=\"text\" id=\"writer\" name=\"writer\" maxlength=\"20\" required=\"required\" value=\"{$writer}\" /></td>
    </tr>
    <tr>
      <td>メッセージ</td>
      <td colspan=\"4\">
        <input type=\"button\" id=\"previewButton\" value=\"プレビュー\" />
        <input type=\"checkbox\" id=\"isRealTime\" name=\"isRealTime\" value=\"1\" />リアルタイムプレビュー<br />
        <textarea rows=\"10\" cols=\"60\" id=\"message\" name=\"message\" required=\"required\">{$message}</textarea>
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        TwitterID
      </td>
      <td>
        <input type=\"text\" id=\"twitterID\" class=\"DirectInput\" name=\"twitterID\" maxlength=\"30\" placeholder=\"@twitter の ID\" value=\"{$twitter_id}\" />
      </td>
      <td>
        mixiID
      </td>
      <td>
        <input type=\"text\" id=\"mixiID\" class=\"DirectInput\" name=\"mixiID\" maxlength=\"10\" placeholder=\"mixi の ID\" value=\"{$mixi_id}\" /><br />
      </td>
    </tr>
    <tr>
      <td></td>
      <td>
        FacebookID
      </td>
      <td>
        <input type=\"text\" id=\"facebookID\" class=\"DirectInput\" name=\"facebookID\" maxlength=\"20\" placeholder=\"facebook の ID\" value=\"{$facebook_id}\" />
      </td>
      <td>編集用パスワード</td>
      <td><input type=\"password\" id=\"password\" name=\"password\" style=\"ime-mode:disabled;\" maxlength=\"20\" /></td>
    </tr>
  <!--
    <tr>
      <td>WebSite</td>
      <td colspan=\"4\"><input type=\"text\" id=\"URL\" /></td>
    </tr>
  -->
    <tr>
      <td>文字色</td>
      <td colspan=\"4\">
        <select name=\"color\" id=\"color\">
          <option value=\"black\">黒</option>
          <option value=\"blue\"{$blue_color}>青</option>
          <option value=\"green\"{$green_color}>緑</option>
          <option value=\"red\"{$red_color}>赤</option>
        </select>
      </td>
    </tr>
    <tr>
      <td></td>
      <td  colspan=\"4\">
        <input type=\"submit\" value=\"投稿する\" />
        <input type=\"reset\" value=\"やり直し\" />
      </td>
    </tr>
  </tbody>
  </table>
    <input type=\"hidden\" value=\"\" name=\"taskId\" id=\"taskId\" />
    <input type=\"hidden\" value=\"{$topic_id}\" name=\"topic_id\" id=\"topic_id\" />
    <input type=\"hidden\" value=\"{$post_id}\" name=\"post_id\" id=\"post_id\" />
    <input type=\"hidden\" value=\"{$mode}\" name=\"mode\" id=\"mode\" />
  </form>
  </div>

  <div id=\"Error\" style=\"color:red;\"></div>
  <div id=\"MathOutput\"></div>
  <div id=\"overlay\" onclick=\"cancelOnClick();\"></div>

    <div id=\"PostStyle\">
      <form action=\"{$formAction}\" onsubmit=\"return formOnPost();\" method=\"POST\" >
        <input type=\"hidden\" value=\"\" name=\"preTaskId\" id=\"preTaskId\" />
      </form>
    </div>
    <div id=\"postControl\">
    </div>";
  return $html;
}
?>
