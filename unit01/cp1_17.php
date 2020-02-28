<?php
//DUPLICATE NOTES: 1st and 20th Qs need button mods so don't use for dups, unit and page need updating on all
//NOTES: answer length needs extending
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
$password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$unit_num = 1;
$page_num = 17;
$username = "{$_SESSION["username"]}{$unit_num}_{$page_num}";

//Get answer if exists
$sql = "SELECT answer FROM ans WHERE username = '{$username}'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $password_err = "Already Answered: " . $row['password'];
        }
        // Free result set
        mysqli_free_result($result);
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Validate answer
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter an answer.";
    } elseif(strlen(trim($_POST["password"])) < 20){
        $password_err = "Answer must be longer.";
    } else{
        $password = trim($_POST["password"]);
    }


    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO ans (username, password) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = $password;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                $password_err = "Submitted";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<script>
//Turn php vars into links
p_num = '<?php echo $page_num ;?>';
u_num = '<?php echo $unit_num ;?>';
n_num = Number(p_num) + 1;
pr_num = Number(p_num) - 1;
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit 1 - Comprehension</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="/mc.css">
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
        <h2 class="text-center" id="currQ">Q1</h2>
        <script>
        //Set current Q number
        document.getElementById("currQ").innerHTML = (`Q${p_num}`);
        </script>
        <p>A cockpit drill/pre-start check is necessary for all drivers. Briefly explain what an ‘instructor’s cockpit drill’ might entail.
          (Hint: Think about sitting in the left seat – the instructor's seat – of a driving school car)</p>
        <form class="text-center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Answer</label>
                <span class="help-block"><?php echo $password_err; ?></span>
                <textarea style="height:100px" name="password" class="form-control" value="<?php echo $password; ?>"></textarea>
            </div>
            <div class="form-group">
                <input type="button" class="btn btn-default" onclick="location.href='cp'+u_num+'_'+(pr_num)+'.php';" value="Previous">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="button" class="btn btn-default" onclick="location.href='cp'+u_num+'_'+(n_num)+'.php';" value="Next">
            </div>
        </form>
    </div>
</body>
</html>
