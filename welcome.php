<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="css/home.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        </button>
        <a class="navbar-brand" href="index.html">ADI Training</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="page-header">
      <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to ADI Theory.</h1>
  </div>

  <div class="menu-wrap">
    <ul class="menu">
        <li>
            <a href="unit01/unit1.php">Unit 1</a>
        </li>
        <li>
            <a href="unit02/unit2.php">Unit 2</a>
        </li>
        <li>
            <a href="unit03/unit3.php">Unit 3</a>
        </li>
        <li>
            <a href="unit04/unit4.php">Unit 4</a>
        </li>
        <li>
            <a href="unit05/unit5.php">Unit 5</a>
        </li>
        <li>
            <a href="#">Hazard Perception</a>
        </li>
        <li>
            <a href="#">Coming Soon</a>
        </li>
    </ul>
</div>
</body>
</html>
