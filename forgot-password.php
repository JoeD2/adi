<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $username_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Check if username is empty
  if(empty(trim($_POST["username"]))){
      $username_err = "Please enter email.";
  } else{
      $username = trim($_POST["username"]);
  }

  // Validate credentials
  if(empty($username_err)){
      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE username = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_username);

          // Set parameters
          $param_username = $username;

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              // Store result
              mysqli_stmt_store_result($stmt);

              // Check if username exists, if yes then verify password
              if(mysqli_stmt_num_rows($stmt) == 1){
                    $_SESSION["user"] = $username;
                    header("location: new-password.php");
                    exit;
              }
              else{
                  // Display an error message if username doesn't exist
                  $username_err = "No account found with that username.";
              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
        // Close statement
        mysqli_stmt_close($stmt);
    }
  }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper text-center">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
