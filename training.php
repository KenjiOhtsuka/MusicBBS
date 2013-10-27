<?php
  require_once('constants.php');
?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>ABC記法 練習場 - <?php echo ConstText::BBStitle; ?></title>
<?php
//  include('meta.php');
  include('headerScript.php');
  include('css.php');
?>
</head>
<body>
<h1><?php echo ConstText::BBStitle; ?> - ABC記法 練習場</h1>
<?php
  include('headerPanel.php');
?>
<script type="text/javascript">
var preMessage = "";
// Use a closure to hide the local variables from the
// global namespace
(function () {
  window.Sanitize = function(text) {
    var result = text;
    result = result.replace(/\</g,'&lt;');
    result = result.replace(/\>/g,'&gt;');
    result = result.replace(/\r\n|\r|\n/g, '<br />');
    return result;
  }
window.UpdateMath = function () {
var text = document.getElementById('InputTextarea').value;
text = Sanitize(text);
//document.getElementById("TrainMathOutput").innerHTML = text;
}
  window.changeRealTime = function (isRealTime) {
    if (isRealTime) {
      document.getElementById('previewButton').disabled = true;
      messageOnKeyUp();
    } else {
      document.getElementById('previewButton').disabled = false;
    }
  }
  window.getRealTime = function () {
    return document.getElementById('isRealTime').checked;
  }
})();
  function messageOnKeyUp() {
    if(getRealTime()) {
      var message = encodeURIComponent(document.getElementById('InputTextarea').value);
      if ((message != preMessage) || changed) {
        preMessage = message;
        UpdateMath();
      }
    }
  }
</script>

<?php
  $title = "ABC記法 練習場";
  $message = "ここでは、ABC記法 の練習ができます。<br />";
  //$message .= "リアルタイムプレビューにチェックを入れると、テキストを編集する度に、随時結果が表示されます。ただしその場合、処理が増えるためレスポンスが遅くなります。<br />";
  $message .= "javascript を使用しています。";
  echo createIntroductionHtml($title, '', '', '', 'black', $message);
?>
<div class="inputArea">
<textarea cols="80" rows="15" id="InputTextarea" name="InputTextarea" onkeyup="messageOnKeyUp();">
X:1
T:Night
C:Sample Composer
M:12/8
K:Am
V:1 clef=treble+8
E3|(AcB) (AcB) (AcB) !tenuto!.e2 e|def edB (c2B) A2E|(AcB) (AcB) (AcB) .!tenuto!e2 e|def edB (c2B) A2e|
.d z c.B z c.A z c.e z e|.d z c.B z c.A z2 E3|(AcB) (AcB) (AcB) !tenuto!.e2 e|def edB (c2B) A2E|
(AcB) (AcB) (AcB) !tenuto!.e2 e|def edB (c2B) A2e|.d z c.B z c.A z c.e z e|.d z c.B z c.a z2 z3|]
V:2 clef=bass
z3|z12|z12|z12|z12|
z12|z6z3 z2 E,,|.A,,3 .E,,3 .A,,3 .E,3 |.D,3 .B,,3 .C,3 .A,,2 E,,|
.A,,3 .E,,3 .A,,3 .E,3 |.D,3 .B,,3 .C,3 .A,,2 E,|.F, z E,.D, z F,.E, z E,.A,, z C,|.B,, z A,,.G,, z A,,.A,, z2 z3|]</textarea>
</div>
<div id="TrainABCOutput" style="background-color:white;">
</div>
<div id="midi"></div>
<div id="warnings"></div>
<script type="text/javascript">
	window.onload = function() {
		abc_editor = new ABCJS.Editor("InputTextarea", { paper_id: "TrainABCOutput", midi_id:"midi", warnings_id:"warnings" });
	}
</script>
</body>
</html>

