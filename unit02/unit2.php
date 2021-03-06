<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/config_answer.php");
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /login.php");
    exit;
}

// Check if the user has paid, if not then redirect him to pay page
if($_SESSION["paid"] == 'No'){
    header("location: /pay.html");
    exit;
}

//Declare variables (change unit_num)
$unit_num = 2;
$last_answered_cp = 1;
$last_answered_mc = 1;
$complete_mc = 0;
$complete_cp = 0;
$complete_rs = 0;

$page_num = 1;
//Check if cp completed
while ($page_num <= 20) {
    $sql = "SELECT answer FROM ans WHERE username = '{$_SESSION["username"]}{$unit_num}_{$page_num}'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $complete_cp = $complete_cp + 1;
            $last_answered_cp = $page_num;
            // Free result set
            mysqli_free_result($result);
        } else {break;}
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    $page_num = $page_num + 1;
}

$page_num = 1;
//Check if mc completed
while ($page_num <= 20) {
    $sql = "SELECT answer FROM ans WHERE username = '{$_SESSION["username"]}{$unit_num}_{$page_num}_mc'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $complete_mc = $complete_mc + 1;
            $last_answered_mc = $page_num;
            // Free result set
            mysqli_free_result($result);
        } else {break;}
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    $page_num = $page_num + 1;
}

$page_num = 1;
//Check if rs completed
while ($page_num <= 6) {
    $sql = "SELECT answer FROM ans WHERE username = '{$_SESSION["username"]}{$unit_num}_{$page_num}_rs'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $complete_rs = $complete_rs + 1;
            // Free result set
            mysqli_free_result($result);
        } else {break;}
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    $page_num = $page_num + 1;
}

// Close connection
mysqli_close($link);
?>

<script>
u_num = '<?php echo $unit_num ;?>';
lanswer_mc = '<?php echo $last_answered_mc ;?>';
lanswer_cp = '<?php echo $last_answered_cp ;?>';
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit 2</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="/css/home.css">
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
        <a class="navbar-brand" href="/welcome.php">Back</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <h1>This unit explores: </h1>
  <p class="summary">Moving off downhill: The process required to safely move off downhill. <br>
Use of the footbrake when stopping: Brake checks and the correct use of the footbrake for smooth stopping. <br>
Mirrors use/teaching/vehicle regulations: The required vehicle mirrors, correct usage and teaching mirror use. <br>
Vehicle sub-systems: The engine and transmission. <br>
Using questions in teaching: Dealing with incorrect or poor answers.
</p>

  <div class="menu-wrap">
    <ul class="menu">
        <li>
            <a href ="" onclick="location.href='/unit02/mc'+u_num+'_'+lanswer_mc+'.php';return false;">Multiple Choice</a>
        </li>
        <span class="help-block"><?php echo $complete_mc; ?>/20</span>
        <li>
            <a href ="" onclick="location.href='/unit02/rs'+u_num+'_1.php';return false;">Road Signs</a>
        </li>
        <span class="help-block"><?php echo $complete_rs; ?>/6</span>
        <li>
            <a href ="" onclick="location.href='/unit02/cp'+u_num+'_'+lanswer_cp+'.php';return false;">Comprehension</a>
        </li>
        <span class="help-block"><?php echo $complete_cp; ?>/20</span>
    </ul>
</div>
</body>
</html>
