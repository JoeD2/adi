<?php
// setup
require_once "config.php";
$result = mysql_query("SELECT * FROM users", $link);
$num_rows = mysql_num_rows($result);
echo "$num_rows Rows\n";

// the condition
if (num_rows != 5) {
  // the message
  $msg = "You have a new User";

  // use wordwrap() if lines are longer than 70 characters
  $msg = wordwrap($msg,70);

  // send email
  mail("joseph-k-douglas@hotmail.co.uk","You have a new User",$msg);
}
mysqli_close($link);
?>
