<?php
  require_once('constants.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>説明 - <?php echo ConstText::BBStitle; ?></title>
<?php
  include('meta.php');
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
  $message = "この掲示板では $\TeX$ を利用して数式を書くことができます。$ \\$ $ 〜 $ \\$ $ で数式を囲むとインラインで、$ \\$\\$ $ 〜 $ \\$\\$ $, $\backslash [$ 〜 $\backslash ] $ などで囲むと別の行として数式を表示できます。";
  $message .= "<br /><br />ただし、$\TeX$ のすべての機能が使えるわけではありません。";
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
  
  $title = '$\TeX$ とは';
  $message = "世界中で広く使われている、高性能でフリーのオープンソース組版ソフトです。";
  $message .= "スタンフォード大学のクヌース教授が自分自身の著書をコンピュータを使って作成するために作ったのが始まりで、今や大学の数学では $\TeX$ が使えないと話になりません。";
  $message .= "Microsoft Office の Word でも数式はだいぶ使えるようになりましたが、数式をヘビーに使う人たちにとってはまだまだで、長い積分評価を書けばメモリを食いまくってしまいます。";
  $message .= "<br /><br />その $\TeX$ に早く慣れるためにも、この掲示板では $\TeX$ を使えるようにしました。といっても一部ですが。";
  $message .= "<br /><br />$\TeX$ に似た奴に $\LaTeX$, $ \LaTeX 2 \epsilon$ などがあります。これらは $\TeX$ から派生して作られたもので、$\TeX$ をより便利にしたものです。";
  $message .= "ちなみにアメリカ数学会は AMS-LaTeX を作りました。まとめて $\TeX$ と呼ばれます。";
  $message .= "パッケージも豊富にあり、<strong>化学</strong>の構造式から、<strong>将棋</strong>の絵を書いたり<strong>楽譜</strong>を書いたりするものまでさまざまです。";
  $message .= "<hr />リンク: <a href=\"http://www.latex-project.org/\" target=\"_blank\" title=\"LaTeX – A document preparation system\">$\LaTeX$ のページ</a> ";
  $message .= ', <a target="_blank" href="http://www.amazon.co.jp/mn/search/?_encoding=UTF8&x=0&tag=mezurashinews-22&linkCode=ur2&y=0&camp=247&creative=7399&field-keywords=latex&url=search-alias%3Daps">$\TeX$ の参考書</a><img src="http://www.assoc-amazon.jp/e/ir?t=mezurashinews-22&l=ur2&o=9" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
  
  $title = '$\TeX$ の書き方';
  $message = '本掲示板での式の書き方例を載せています。さらに詳しい書き方については、"TeX" をキーワードに検索してみてください。';
  $message .= '<h3>式の書き方例</h3><p>簡単な式の書き方を載せています。式を書く際の参考にしてください。</p>';
  $message .= '
  <table class="texTable">
    <thead>
      <tr><td>式</td><td>$TeX$ での書き方</td></thead>
    <tbody>
      <tr><td>$$4 \div 5 \times 3 = \frac{12}{5}$$</td><td>4 \div 5 \times 3 = \frac{12}{5}</td></tr>
      <tr><td>$$f(x) = \sqrt[3]{3}x^2 + \pi x - 1$$</td><td>f(x) = \sqrt[3]{3}x^2 + \pi x - 1</td></tr>
      <tr><td>$$a_{n+1} = a_{n} + 3$$</td><td>a_{n+1} = a_{n} + 3</td></tr>
      <tr><td>$$\vec{a} = \overrightarrow{OA}$$</td><td>\vec{a} = \overrightarrow{OA}</td></tr>
      <tr><td>$$\vec{a} \cdot \vec{b} = |\vec{a}| |\vec{b}| \cos \theta$$</td><td>\vec{a} \cdot \vec{b} = |\vec{a}| |\vec{b}| \cos \theta</td></tr>
    </tbody>
  </table>';
  $message .= '<h3>英数字</h3><p>$x$ などの文字や数字は、半角で入力すると表示されます。</p>';
  $message .= '<h3>ギリシャ文字</h3><p>円周率 $\pi$ などのギリシャ文字は特殊な書き方をします。</p>';
  $message .= '
  <table class="texTable">
    <thead>
      <tr><td>記号</td><td>日本名</td><td>書き方</td></thead>
    <tbody>
      <tr><td>$\alpha$</td><td>アルファ</td><td>\alpha</td></tr>
      <tr><td>$\beta$</td><td>ベータ</td><td>\beta</td></tr>
      <tr><td>$\gamma$</td><td>ガンマ</td><td>\gamma</td></tr>
      <tr><td>$\delta$</td><td>デルタ</td><td>\delta</td></tr>
      <tr><td>$\theta$</td><td>シータ</td><td>\theta</td></tr>
      <tr><td>$\pi$</td><td>パイ</td><td>\pi</td></tr>
      <tr><td>$\sigma$</td><td>シグマ</td><td>\sigma</td></tr>
    </tbody>
  </table>';
  $message .= '
  <table class="texTable">
    <thead>
      <tr><td>記号</td><td>日本名</td><td>書き方</td></thead>
    <tbody>
      <tr><td>$A$</td><td>アルファ</td><td>A</td></tr>
      <tr><td>$B$</td><td>ベータ</td><td>B</td></tr>
      <tr><td>$\Gamma$</td><td>ガンマ</td><td>\Gamma</td></tr>
      <tr><td>$\Delta$</td><td>デルタ</td><td>\Delta</td></tr>
      <tr><td>$\Theta$</td><td>シータ</td><td>\Theta</td></tr>
      <tr><td>$\Pi$</td><td>パイ</td><td>\Pi</td></tr>
      <tr><td>$\Sigma$</td><td>シグマ</td><td>\Sigma</td></tr>
    </tbody>
  </table>';

  echo createIntroductionHtml($title, '', '', '', 'black', $message);
?>
