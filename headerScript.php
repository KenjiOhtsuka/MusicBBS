<?php
  echo <<< EOT
  <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="js/abcjs_basic_1.5-min.js"></script>
  <script type="text/javascript" src="js/abcjs_editor_1.5-min.js"></script>

EOT;
  
  if ($pattern == PageType::Unknown || $pattern == PageType::Comment ||
      $pattern == PageType::Board || $pattern == PageType::Topic) {
    echo '  <script type="text/javascript" src="js/inputScript.js"></script>';
  }
?>
