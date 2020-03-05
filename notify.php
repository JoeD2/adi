<?php

require_once "config.php";
// Check connection
if ($link -> connect_errno) {
  echo "Failed to connect to MySQL: " . $link -> connect_error;
  exit();
}

// Perform query
if ($result = $link -> query("SELECT * FROM users")) {
  echo "Returned rows are: " . $result -> num_rows;
  if ($result -> num_rows != 6) {
    // the message
    $msg = "You have a new User";

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    // send email
    mail("joseph-k-douglas@hotmail.co.uk","You have a new User",$msg);
  }
  // Free result set
  $result -> free_result();
}

$link -> close();
?>
