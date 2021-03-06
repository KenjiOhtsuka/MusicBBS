var preMessage = "";
var changed = false;
var ChangeCount = 0;

function getXHR() {
  var req;
  try {
    req = new XMLHttpRequest();
  } catch (e) {
    try {
      req = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
      req = new ActiveXObject('Microsoft.XMLHTTP');
    }
  }
  return req;
}

function isZealNumber(text) {
  return text.toString().match(/^[1-9][0-9]*$/);
}
function isAlphaNumberString(text) {
  return text.toString().match(/[a-zA-Z0-9]*/);
}

  (function () {
    window.Sanitize = function(text) {
      var result = text;
      result = result.replace(/\</g,'&lt;');
      result = result.replace(/\>/g,'&gt;');
      //result = result.replace(/(\\$\\$[^\\$\\r\\n]*[(\\r\\n)\\r\\n])/g, ' $$ ');
      //result = result.replace(/([(\\r\\n)\\r|\\n][^\\$\\r\\n]*\\$\\$)/g, ' $$ ');
      //result = result.replace(/[(\\r\\n)\\r\\n]/g, '<br />');
      result = result.replace(/\r\n/g, '<br />');
      result = result.replace(/\r/g, '<br />');
      result = result.replace(/\n/g, '<br />');
      return result;
    }
  })();

  function asyncSend() {
    var req = getXHR();
    req.onreadystatechange = function() {
      var result = document.getElementById('PostStyle');
      if (req.readyState == 4) {
        if (req.status == 200) {
          result.innerHTML = req.responseText;
          changed = true;
        } else {
          result.innerHTML = "Server Error!";
        }
      } else {
        result.innerHTML = "☆ただいま通信中☆";
      }
    }
    req.open('POST', 'verify.php', true);
    req.setRequestHeader('content-type', 'application/x-www-form-urlencoded;charset=UTF-8');
    var param = 'title=' + encodeURIComponent(document.getElementById('title').value);
    param += '&writer=' + encodeURIComponent(document.getElementById('writer').value);
    param += '&message=' + encodeURIComponent(document.getElementById('message').value);
    param += '&twitterID=' + encodeURIComponent(document.getElementById('twitterID').value);
    param += '&mixiID=' + encodeURIComponent(document.getElementById('mixiID').value);
    param += '&facebookID=' + encodeURIComponent(document.getElementById('facebookID').value);
    param += '&topic_id=' + encodeURIComponent(document.getElementById('topic_id').value);
    param += '&post_id=' + encodeURIComponent(document.getElementById('post_id').value);
    req.send(param);
  }
    function verify(inStrict) {
      var errMsg = "";
      if (inStrict && (document.ScorePad.title.value.replace(/\s|　/g, '') == "")) {
        errMsg += "タイトルを30文字以内で入力してください。<br />";
      } else if (document.ScorePad.title.value.length > 30) {
        errMsg += "タイトルは30文字以内で入力してください。<br />";
      }
      if (inStrict && (document.ScorePad.writer.value.replace(/\s|　/g, '') == "")) {
        errMsg += "名前を20文字以内で入力してください。<br />";
      } else if (document.ScorePad.writer.value.length > 20) {
        errMsg += "名前は20文字以内で入力してください。<br />";
      }
      if (inStrict && (document.ScorePad.message.value.replace(/\s|　/g, '') == "")) {
        errMsg += "メッセージを20,000文字以内で入力してください。<br />";
      } else if (document.ScorePad.message.value.length > 20000) {
        errMsg += "メッセージは20,000文字以内で入力してください。<br />";
      }
      if (document.ScorePad.twitterID.value.length > 30)
        errMsg += "twitterID は30文字以内で入力してください。<br />";
      if ((document.ScorePad.mixiID.value.length != 0) &&
          ((document.ScorePad.mixiID.value.length > 10) ||
            !isZealNumber(document.ScorePad.mixiID.value.toString())))
        errMsg += "mixiID は整数10桁以内で入力してください。<br />";
      if (document.ScorePad.facebookID.value.length > 20)
        errMsg += "facebookID は20文字以内で入力してください。<br />";
      if (document.ScorePad.password.value.length > 20 ||
          !isAlphaNumberString(document.ScorePad.password.value)) {
        errMsg += "パスワードは半角英数20文字以内で入力してください。<br />";
      }
      document.getElementById("Error").innerHTML = errMsg;
      if (errMsg.length == 0) {
        document.getElementById('Error').style.display = 'none';
        return true;
      } else {
        document.getElementById('Error').style.display = 'block';
        return false;
      }
    }
    function controlConfirmBox(value){
      document.getElementById('overlay').style.display = value;
      document.getElementById('PostStyle').style.display = value;
    }

    function formOnConfirm() {
      if (!verify(true)) return false;
      if (document.getElementById('overlay').style.display != 'block') {
        window.scroll(0,0);
        controlConfirmBox('block');
        asyncSend();
        return false;
      } else {
        return true;
      }
    }
    function cancelOnClick() {
      controlConfirmBox('none');
    }
    function formOnPost() {
      document.getElementById('taskId').value = document.getElementById('preTaskId').value;
      document.ScorePad.submit();
      return false;
    }

window.onload = function() {
  document.getElementById('ScorePad').onsubmit = function() {return formOnConfirm();};
  document.getElementById("insertBase").onclick = function() {
    baseScore = "X:1\nM:4/4\nK:C\nV:1 clef=treble\nE2|(3(AcB) (3(AcB) (3(AcB) !tenuto!.e3/2 e/2|(3def (3edB (c3/2B/2) A3/2 E/2|(3(AcB) (3(AcB) (3(AcB) .!tenuto!e3/2 e/2|(3def (3edB (c3/2B/2) A2|]";
    document.getElementById('abcScore').value = baseScore;
  };
  document.getElementById("clearScore").onclick = function() {
    document.getElementById('abcScore').value = '';
  };
  abc_editor = new ABCJS.Editor("abcScore", { paper_id: "abcOutput", midi_id:"midi", warnings_id:"warnings" });
}
