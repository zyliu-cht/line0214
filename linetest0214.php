<?php
  $json_str = file_get_contents('php://input'); //get json string from body
  $json = json_decode($json_str); //convert to json object
  
  $file = fopen("log.txt", "w+") or die("unable to open log file!");  //open log file or alert a fail msg
  fwrite($file, "\xEF\xBB\xBF".$json_str);  //write json to log
?>
