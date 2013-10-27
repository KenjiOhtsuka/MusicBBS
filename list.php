<?php

switch ($pattern) {
  case PageType::Board: case PageType::Unknown:
    $n = 0;
    $page_number = 1;
    if ($pattern == PageType::Board) {
      $page_number = $_GET['page_number'];
      $n = ($page_number - 1) * ConstParam::BoardTopicCount;
    }

    $sqlHead = "SELECT count(music_topics.id) id_count FROM music_topics";
    $rowsHead = mysql_query($sqlHead);
    if (!$rowsHead) {
      die(mysql_error());
    }
    if ($rowHead = mysql_fetch_array($rowsHead)) {
      if ($rowHead['id_count'] >= $page_number) {
        $pager = createPager($page_number, $rowHead['id_count'], ConstParam::BoardTopicCount, ConstParam::Pager, GetParam::PageNumber);
      } else {
        $pager = "<p></p>";
      }
    }
    echo $pager;
    echo "<hr />";

    // get topic top posts with comments count
    $sqlHead = "SELECT postsA.topic_id, postsA.writer,"
              ."postsA.title, postsA.message, postsA.twitter_id,"
              ."postsA.mixi_id, postsA.facebook_id, postsA.url,"
              ."postsA.color, postsA.created, postsA.modified, ms.score, postsC.id_count ";
    $sqlHead .= "FROM (SELECT id, updated FROM music_topics "
               ."       ORDER BY updated DESC LIMIT ".(string)$n.", ".(string)ConstParam::BoardTopicCount.") "
               ."     music_topics "
               ."JOIN music_posts postsA ON postsA.topic_id = music_topics.id AND postsA.id = 0 "
               ."JOIN (SELECT postsB.topic_id topic_id, count(postsB.id) id_count "
               ."        FROM music_posts postsB"
               ."       GROUP BY postsB.topic_id) postsC "
               ."  ON postsC.topic_id = music_topics.id "
               ."LEFT JOIN music_scores ms "
               ."  ON ms.topic_id = postsA.topic_id "
               ." AND ms.post_id = postsA.id "
               ."ORDER BY music_topics.updated DESC";

    $rowsHead = mysql_query($sqlHead, $myCon);
    if (!$rowsHead) {
      die(mysql_error());
    }
    while($rowHead = mysql_fetch_array($rowsHead)) {
      echo "<div class=\"topic\">";
      echo createTopicHtml($rowHead['topic_id'], $rowHead['title'], $rowHead['writer'], 
                         $rowHead['twitter_id'], $rowHead['mixi_id'], $rowHead['facebook_id'], 
                         $rowHead['color'], nl2br($rowHead['message']), $rowHead['created'], $rowHead['modified'],
                         stripslashes($rowHead['score']));

      // sql to get comments
      $sqlPost = "SELECT mp.id post_id, mp.writer, mp.title, mp.message, mp.twitter_id,"
                ."mp.mixi_id, mp.facebook_id, mp.url, mp.color, mp.created, mp.modified,"
                ."ms.score ";
      $sqlPost .= " FROM music_posts mp "
                 ." LEFT JOIN music_scores ms \n"
                 ."   ON ms.post_id = mp.id \n"
                 ."  AND ms.topic_id = mp.topic_id "
                 ."WHERE mp.topic_id = {$rowHead['topic_id']} AND mp.id != 0 "
                 ."ORDER BY mp.id DESC LIMIT 0, ".(string)ConstParam::BoardTopicCommentCount;
      $rowsPost = mysql_query($sqlPost, $myCon);
      if (!$rowsPost) {
        die(mysql_error());
      }
      $html = "";
      while($rowPost = mysql_fetch_array($rowsPost)) {
        $html = createCommentHtml($rowHead['topic_id'], $rowPost['post_id'], $rowPost['title'], $rowPost['writer'], 
                               $rowPost['twitter_id'], $rowPost['mixi_id'], $rowPost['facebook_id'],
                               $rowPost['color'], nl2br($rowPost['message']), $rowPost['created'], $rowPost['modified'],
                               stripslashes($rowPost['score']))
              .$html;
      }
      if ($rowHead['id_count'] > ConstParam::BoardTopicCommentCount + 1) {
        $html = "<div style=\"text-align:center;\">(コメント一部省略)</div>".$html;
      }
      echo $html;
      echo "  </div>";
    }
    echo "  </div>";

    echo "<hr />";
    echo $pager;

    break;

  case PageType::Comment:
    $topic_id = (int)$_GET[GetParam::TopicId];
    /* create pager - start - */
    $sqlHead = "SELECT count(mp.id) id_count FROM music_posts mp WHERE mp.topic_id = {$topic_id}";
    $rowsHead = mysql_query($sqlHead);
    if (!$rowsHead) {
      die(mysql_error());
    }
    $sqlPost = "SELECT count(mp.id) id_count FROM music_posts mp WHERE mp.topic_id = {$topic_id} AND id BETWEEN 1 AND {$_GET[GetParam::PostId]}";
    $rowsPost = mysql_query($sqlPost);
    if (!$rowsPost) {
      die(mysql_error());
    }
    if ($rowHead = mysql_fetch_array($rowsHead)) {
      if ($rowPost = mysql_fetch_array($rowsPost)) {
        $page_number = ceil($rowPost['id_count'] / ConstParam::BoardTopicCount);

        if ($rowHead['id_count'] >= $page_number) {
          echo createPager($page_number, $rowHead['id_count'], ConstParam::TopicCommentCount, ConstParam::Pager,
                         GetParam::TopicId."={$topic_id}&".GetParam::PageNumber);
        } else {
          echo "<p></p>";
        }
      }
    }
    /* create pager - end -*/
    echo $pager;
    echo "<hr />";

    $sqlHead = "SELECT mp.topic_id, mp.id post_id, mp.writer,"
              ."mp.title, mp.message, mp.twitter_id,"
              ."mp.mixi_id, mp.facebook_id, mp.url,"
              ."mp.color, mp.created, mp.modified, ms.score ";
    $sqlHead .= "FROM music_topics mt "
               ."JOIN music_posts mp ON mp.topic_id = mt.id AND mp.id = 0 "
               ." LEFT JOIN music_scores ms "
               ."   ON ms.post_id = mp.id "
               ."  AND ms.topic_id = mp.topic_id "
               ."WHERE mt.id = {$topic_id}";
    $rowsHead = mysql_query($sqlHead, $myCon);
    if (!$rowsHead) {
      die(mysql_error());
    }
    if ($rowHead = mysql_fetch_array($rowsHead)) {
      echo "  <div class=\"topic\">";
      echo createTopicHtml($topic_id, $rowHead['title'], $rowHead['writer'], 
                         $rowHead['twitter_id'], $rowHead['mixi_id'], $rowHead['facebook_id'], 
                         $rowHead['color'], nl2br($rowHead['message']), $rowHead['created'], $rowHead['modified'],
                         stripslashes($rowHead['score']));
      $sqlPost = "SELECT mp.id post_id, mp.writer, mp.title, mp.message, mp.twitter_id,"
                ."mp.mixi_id, mp.facebook_id, mp.url, mp.color, mp.created, mp.modified,"
                ."ms.score ";
      $sqlPost .= "FROM music_posts mp "
                 ."LEFT JOIN music_scores ms \n"
                 ."  ON ms.post_id = mp.id \n"
                 ." AND ms.topic_id = mp.topic_id \n"
                 ."WHERE mp.topic_id = {$topic_id} AND mp.id != 0 "
                 ."ORDER BY mp.id DESC LIMIT 0, ".(string)ConstParam::TopicCommentCount;
      $rowsPost = mysql_query($sqlPost, $myCon);
      if (!$rowsPost) {
        die(mysql_error());
      }
      $post_id = 1;
      $html = "";
      while($rowPost = mysql_fetch_array($rowsPost)) {
        $post_id = $rowPost['post_id'];
        $html = createCommentHtml($rowHead['topic_id'], $rowPost['post_id'], $rowPost['title'], $rowPost['writer'], 
                               $rowPost['twitter_id'], $rowPost['mixi_id'], $rowPost['facebook_id'],
                               $rowPost['color'], nl2br($rowPost['message']), $rowPost['created'], $rowPost['modified'],
                               stripslashes($rowPost['score']))
               .$html;
      }
      echo $html;
      echo "  </div>\n";
      echo "  </div>\n";
    } else {
      echo "<p>指定されたトピックがありません。</p>";
    }

    echo "<hr />";
    $sqlHead = "SELECT count(mp.id) id_count FROM music_posts mp WHERE mp.topic_id = {$topic_id}";
    $rowsHead = mysql_query($sqlHead);
    if (!$rowsHead) {
      die(mysql_error());
    }
    $sqlPost = "SELECT count(mp.id) id_count FROM music_posts mp WHERE mp.topic_id = {$topic_id} AND id BETWEEN 1 AND {$_GET[GetParam::PostId]}";
    $rowsPost = mysql_query($sqlPost);
    if (!$rowsPost) {
      die(mysql_error());
    }

    if ($rowHead = mysql_fetch_array($rowsHead)) {
      if ($rowPost = mysql_fetch_array($rowsPost)) {
        $page_number = ceil($rowPost['id_count'] / ConstParam::BoardTopicCount);

        if ($rowHead['id_count'] >= $page_number) {
          echo createPager($page_number, $rowHead['id_count'], ConstParam::TopicCommentCount, ConstParam::Pager,
                         GetParam::TopicId."={$topic_id}&".GetParam::PageNumber);
        } else {
          echo "<p></p>";
        }
      }
    }
    break;

  case PageType::Topic:
    $topic_id = (int)$_GET['topic_id'];
    if (isset($_GET[GetParam::PageNumber]) && ctype_digit($_GET[GetParam::PageNumber]) && $_GET[GetParam::PageNumber] != 0) {
      $page_number = $_GET[GetParam::PageNumber];
    } else {
      $page_number = 1;
    }

    $sqlPost = "SELECT count(music_posts.id) id_count FROM music_posts WHERE music_posts.topic_id = {$topic_id}";
    $rowsPost = mysql_query($sqlPost);
    if (!$rowsPost) {
      die(mysql_error());
    }
    if ($rowPost = mysql_fetch_array($rowsPost)) {
      if ($rowPost['id_count'] >= $page_number) {
        $pager = createPager($page_number, $rowPost['id_count'], ConstParam::TopicCommentCount, ConstParam::Pager, 
                        GetParam::TopicId."={$topic_id}&".GetParam::PageNumber);
      } else {
        $pager = "<p></p>";
      }
    }
    echo $pager;
    echo "<hr />";

    $sqlHead = "SELECT mp.topic_id, mp.id post_id, mp.writer, mp.title, mp.message, mp.twitter_id,"
              ."mp.mixi_id, mp.facebook_id, mp.url, mp.color, mp.created, mp.modified, ms.score ";
    $sqlHead .= "FROM music_topics mt "
               ."JOIN music_posts mp ON mp.topic_id = mt.id AND mp.id = 0 "
               ."LEFT JOIN music_scores ms "
               ."  ON ms.post_id = mp.id "
               ." AND ms.topic_id = mp.topic_id "
               ."WHERE mt.id = {$topic_id}";

    $rowsHead = mysql_query($sqlHead, $myCon);
    if (!$rowsHead) {
      die(mysql_error());
    }
    $n = ($page_number - 1) * ConstParam::TopicCommentCount;
    if ($rowHead = mysql_fetch_array($rowsHead)) {
      $htmlTitle = $rowHead['title'];
      echo "  <div class=\"topic\">";
      echo createTopicHtml($rowHead['topic_id'], $rowHead['title'], $rowHead['writer'], 
                         $rowHead['twitter_id'], $rowHead['mixi_id'], $rowHead['facebook_id'], 
                         $rowHead['color'], nl2br($rowHead['message']), $rowHead['created'], $rowHead['modified'],
                         stripslashes($rowHead['score']));

      $sqlPost = "SELECT mp.id post_id, mp.writer, mp.title, mp.message, mp.twitter_id,"
                ."mp.mixi_id, mp.facebook_id, mp.url, mp.color, mp.created, mp.modified, ms.score ";
      $sqlPost .= "FROM music_posts mp "
                 ."LEFT JOIN music_scores ms "
                 ."  ON ms.post_id = mp.id "
                 ." AND ms.topic_id = mp.topic_id "
                 ."WHERE mp.topic_id = {$topic_id} AND mp.id != 0 "
                 ."ORDER BY mp.id DESC LIMIT {$n}, ".(string)ConstParam::TopicCommentCount;
      $rowsPost = mysql_query($sqlPost, $myCon);
      if (!$rowsPost) {
        die(mysql_error());
      }
      $post_id = 1;
      $html = "";
      while($rowPost = mysql_fetch_array($rowsPost)) {
        $post_id = $rowPost['post_id'];
        $html = createCommentHtml($topic_id, $rowPost['post_id'], $rowPost['title'], $rowPost['writer'], 
                               $rowPost['twitter_id'], $rowPost['mixi_id'], $rowPost['facebook_id'],
                               $rowPost['color'], nl2br($rowPost['message']), $rowPost['created'], $rowPost['modified'],
                               stripslashes($rowPost['score']))
              .$html;
      }
      if ($post_id != 1) {
        $html = "(一部省略)<br />".$html;
      }
      echo $html;
      echo "  </div>\n";
      echo "  </div>\n";
    } else {
      echo "<p>指定されたトピックがありません。</p>";
    }

    echo "<hr />";
    echo $pager;
    break;

  case PageType::Summery:
    $html = '';
    $page_number = $_GET['summery_number'];
    
    /* create pager - start - */
    $sqlHead = "SELECT count(mt.id) id_count FROM music_topics mt";
    $rowsHead = mysql_query($sqlHead);
    if (!$rowsHead) {
      die(mysql_error());
    }
    if ($rowHead = mysql_fetch_array($rowsHead)) {
      if ($rowHead['id_count'] >= $page_number || $rowHead['id_count'] == 0) {
        $pager = createPager($page_number, $rowHead['id_count'], ConstParam::SummeryCount, ConstParam::Pager, GetParam::SummeryNumber);
      } else {
        $pager = "<p>トップページに戻ってやり直してください。</p>";
      }
    }
    /* create pager - end - */
    echo $pager;
    $html .= $pager."\n<hr />";
    echo "<hr />";

    $n = ($page_number - 1) * ConstParam::SummeryCount;
    $sqlHead = "SELECT mt.id, A.title title, count(B.id) - 1 posts_count, "
              ."mt.updated ";
    $sqlHead .= "FROM (SELECT id, updated FROM music_topics "
               ."      ORDER BY updated DESC LIMIT ".(string)$n.", ".(string)ConstParam::SummeryCount.") mt "
               ."JOIN music_posts A ON A.topic_id = mt.id AND A.id = 0 "
               ."JOIN music_posts B ON B.topic_id = mt.id ";
    $sqlHead .= "GROUP BY mt.id ORDER BY mt.updated DESC";
    $rowsHead = mysql_query($sqlHead, $myCon);
    if (!$rowsHead) {
      die(mysql_error());
    }

    echo '<div id="topicList">';
    $html .= '<div id="topicList">';
    while ($rowHead = mysql_fetch_array($rowsHead)) {
      echo $rowHead['id']." <a href=\"{$_SERVER['PHP_SELF']}?topic_id={$rowHead['id']}\">".$rowHead['title']."(".$rowHead['posts_count'].")</a><br />\n";
      $html .= $rowHead['id']." <a href=\"{$_SERVER['PHP_SELF']}?topic_id={$rowHead['id']}\">".$rowHead['title']."(".$rowHead['posts_count'].")</a><br />\n";
    }
    echo '</div>';
    $html .= "</div>\n<hr />\n".$pager;

    echo "<hr />";
    echo $pager;
    break;
}

?>
