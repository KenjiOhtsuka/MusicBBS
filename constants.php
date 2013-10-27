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
  const URLPrefix = 'http://nippon.vacau.com/ScoreBBS/';
}

function metaTags($title) {
  $code = '
  <meta charset="utf-8" />
  <meta name="description" content="'.ConstText::Description.'" />';
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

function createCommentHtml($topic_id, $post_id, $title, $writer,
  $twitter_id, $mixi_id, $facebook_id, $color = 'black', $message,
  $created, $modified, $score = '') {
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
  if (!empty($score)) {
    $html .= scoreViewHtml($topic_id, $post_id, $score);
  }
  $html .= "    <div class=\"message\" style=\"color:{$color};\">{$message}</div>\n";
  $html .= "    <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::PostId."={$post_id}&".GetParam::Mode."=".ModeType::Edit."\">編集</a>　<a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::PostId."={$post_id}\">コメント</a></div>\n";
  $html .= "  </div>\n";
  return $html;
}
function createTopicHtml($topic_id, $title, $writer, $twitter_id, 
  $mixi_id, $facebook_id, $color = 'black', $message, $created, $modified,
  $score = '') {
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
  if (!empty($score)) {
    $html .= scoreViewHtml($topic_id, 0, $score);
  }
  $html .= "  <div class=\"message\">{$message}</div>\n";
  $html .= "  <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}&".GetParam::Mode."=".ModeType::Edit."\">編集</a>　<a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}\">コメント</a></div>\n";
//  $html .= "  <div class=\"footLink\"><a href=\"{$_SERVER['PHP_SELF']}?".GetParam::TopicId."={$topic_id}\">コメント</a></div>\n";
  $html .= "</div>\n";
  return $html;
}
function createIntroductionHtml($title, $twitter_id, $mixi_id, $facebook_id, $color = 'black', $message) {
  $html = "<div class=\"topic\">\n";
  $html .= "  <h2><a href=\"training.php\">{$title}</a></h2>\n";
  $html .= "  <div style=\"text-align:right;\">\n";
  $html .= createSocialLink($twitter_id, $mixi_id, $facebook_id, ConstText::BBStitle, '');
  $html .= "\n  </div>\n";
  $html .= "  <hr />\n";
  $html .= "  <div class=\"message\" style=\"color:{$color};\">{$message}</div>\n";
  $html .= "</div>\n";
  return $html;
}

function scoreViewHtml($topic_id, $post_id, $score) {
  $html = "  <div id=\"toggle_score_{$topic_id}_{$post_id}\" class=\"slideButton\">Toggle Score</div>\n";
  $html .= "  <div id=\"score{$topic_id}_{$post_id}\" class=\"scoreArea\">\n";
  $html .= "    <textarea id=\"abcScore{$topic_id}_{$post_id}\" name=\"abcScore\" class=\"abcScore\" rows=\"10\" cols=\"200\" readonly=\"readonly\">\n";
  $html .= $score;
  $html .= "\n    </textarea>\n";
  $html .= "  </div>\n";
  $html .= "  <div id=\"notation{$topic_id}_{$post_id}\" name=\"notation\" class=\"notation\"></div>\n";
  $html .= "  <div id=\"midi{$topic_id}_{$post_id}\" name=\"midi\" class=\"\"></div>\n";
  $html .= "  <script>\n";
  $html .= "    $('#toggle_score_{$topic_id}_{$post_id}').click(function(){\n";
  $html .= "      $(\"#score{$topic_id}_{$post_id}\").slideToggle('slow');\n";
  $html .= "    });\n";
  $html .= "    var abcString = document.getElementById('abcScore{$topic_id}_{$post_id}').value;\n";
  $html .= "    ABCJS.renderAbc(\"notation{$topic_id}_{$post_id}\", abcString, {}, {scale: 0.5}, {});\n";
  $html .= "    ABCJS.renderMidi(\"midi{$topic_id}_{$post_id}\", abcString);\n";
  $html .= "  </script>\n";
  return $html;
}

function outputForm($formAction, $title = '', $writer = '', $message = '', $score = '',
                   $mixi_id = '', $twitter_id = '', $facebook_id = '', 
                   $topic_id = '', $post_id = '', $mode = ModeType::View) {
  $html = "
  <div id=\"inputArea\" class=\"inputArea\">
  <form action=\"{$formAction}\" method=\"POST\" name=\"ScorePad\" id=\"ScorePad\">
  <table>
  <tbody>
    <tr>
      <td><p>タイトル</p></td>
      <td colspan=\"4\"><input type=\"text\" id=\"title\" name=\"title\" maxlength=\"30\" value=\"{$title}\" required=\"required\" /></td>
    </tr>
    <tr>
      <td><p>名前</p></td>
      <td colspan=\"4\"><input type=\"text\" id=\"writer\" name=\"writer\" maxlength=\"20\" required=\"required\" value=\"{$writer}\" /></td>
    </tr>
    <tr>
			<td>
        <p>楽譜</p>
      </td>
			<td colspan=\"4\">
			  <input type=\"button\" id=\"insertBase\" value=\"基本データを挿入する\" />
			  <input type=\"button\" id=\"clearScore\" value=\"楽譜をクリアする\" />
        <textarea rows=\"10\" cols=\"80\" id=\"abcScore\" name=\"abcScore\">{$score}</textarea>
        <div id=\"warnings\"></div>
      </td>
    </tr>
    <tr>
      <td><p>メッセージ</p></td>
      <td colspan=\"4\">
        <textarea rows=\"10\" cols=\"80\" id=\"message\" name=\"message\" required=\"required\">{$message}</textarea>
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
      <td><p>編集用パスワード</p></td>
      <td><input type=\"password\" id=\"password\" name=\"password\" style=\"ime-mode:disabled;\" maxlength=\"20\" /></td>
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
  <div class=\"outputContainer\">
    <div id=\"abcOutput\" class=\"abcOutput\"></div>
    <div id=\"midi\"></div>
  </div>
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
