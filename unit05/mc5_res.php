<?php
//DUPLICATE NOTES: 1st and 20th Qs need button mods so don't use for dups, unit and page and c answer need updating on all
//NOTES: question and answers need updating
// Include config file
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

// Define variables and initialize with values
$password = $_SESSION['counter5_mc'];
$username_err = $password_err = "";
$unit_num = 5;
$page_num = 21;
$username = "{$_SESSION["username"]}{$unit_num}_{$page_num}_mc";

//Check if cp completed
$answered = 0;
$check = 1;
$last_answered = 1;
while ($check <= 20) {
    $sql = "SELECT answer FROM ans WHERE username = '{$_SESSION["username"]}{$unit_num}_{$check}_mc'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            $answered = $answered + 1;
            $last_answered = $check;
            // Free result set
            mysqli_free_result($result);
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    $check = $check + 1;
}

if ($answered < 20) {
  echo "You have answered up to question " . $last_answered . ". Please finish to see results";
  $password_err = "Don't Submit PHP";
}

//Get answer if exists
$sql = "SELECT answer FROM ans WHERE username = '{$username}'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          $password_err = "Don't Submit PHP";
          $password = $row['answer'];
        }
        // Free result set
        mysqli_free_result($result);
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Check for answer then prepare an insert statement for score
if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
    $sql = "INSERT INTO ans (username, answer) VALUES (?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set parameters
        $param_username = $username;
        $param_password = $password;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            echo "Saved";
            unset($_SESSION['counter5_mc']);
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}
// Close connection
mysqli_close($link);

?>

<script>
//Turn php vars into links
p_num = '<?php echo $page_num ;?>';
u_num = '<?php echo $unit_num ;?>';
n_num = Number(p_num) + 1;
pr_num = Number(p_num) - 1;
score = '<?php echo $password ;?>';
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit 5 - Multiple Choice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="/css/mc.css">
    <style>
    form {
        display: inline-block;
    }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          </button>
          <a class="navbar-brand" href="unit" onclick="location.href=this.href+u_num+'.php';return false;">Back</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="wrapper">
        <h4 class="question">Multiple Choice</h4>
        <h4 id="scorey" class="u-mb2">Loading</h4>
        <script>
          document.getElementById("scorey").innerHTML = "Score = " + score.toString();
        </script>
        <div class="form-group text-center">
            <input type="button" class="btn btn-default" onclick="location.href='mc'+u_num+'_'+1+'.php';" value="Review">
        </div>
    </div>
    <script src="/script.js"></script>
</body>
</html>
