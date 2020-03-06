<?php

require_once "config.php";
// Check connection
if ($link -> connect_errno) {
  echo "Failed to connect to MySQL: " . $link -> connect_error;
  exit();
}

// Open the users file for reading
$section = file_get_contents('users.txt');
echo $section;

// Perform query
if ($result = $link -> query("SELECT * FROM users")) {
  echo "Returned rows are: " . $result -> num_rows;
  if ($result -> num_rows != $section) {
    // the message
    $msg = "You have a new User";

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    // send email
    mail("joseph-k-douglas@hotmail.co.uk","You have a new User",$msg);

    // Set new users amount
    file_put_contents('users.txt', $result -> num_rows);
  }
  // Free result set
  $result -> free_result();
}

$link -> close();
?>
