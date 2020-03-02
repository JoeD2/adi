<?php
// setup
require_once "config.php";
$stmt = mysqli_prepare($link, "SELECT * FROM users");

// the condition
if (mysqli_stmt_num_rows($stmt) == 5) {
  // the message
  $msg = "You have a new User";

  // use wordwrap() if lines are longer than 70 characters
  $msg = wordwrap($msg,70);

  // send email
  mail("joseph-k-douglas@hotmail.co.uk","You have a new User",$msg);
}
mysqli_close($link);
?>
