<?php
  // Check if the form has been submitted
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validate the form data
    if (empty($username)) {
      $error_message = 'Please enter your username';
    } else if (empty($password)) {
      $error_message = 'Please enter your password';
    } else {
      // Connect to the database
      $db_host = '195.201.179.80';
      $db_user = 'ergopunc_LudMac';
      $db_pass = 'LudMac123';
      $db_name = 'ergopunc_LudMacDB';
      $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
      
      $query = "SELECT * FROM users WHERE username='$username'";
  	  $result = mysqli_query($conn, $query);
  	  if (mysqli_num_rows($result) > 0) {
      // The username is already taken
      die('Username already exists');
  }
  
  // Check if the password meets the security requirements
  if (strlen($password) < 8) {
    // The password is too short
    die('Password must be at least 8 characters long');
  }
  if (!preg_match('/[A-Z]/', $password))
      // Check the connection
      if (!$conn) {
        $error_message = 'Error connecting to the database: ' . mysqli_connect_error();
      } else {
        // Escape the form data for security
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);
        
        // Query the database for the user
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
          // Login successful
          session_start();
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $username;
          header('Location: /dashboard.php');
          exit;
        } else {
          // Login failed
          $error_message = 'Invalid username or password';
        }
      }
    }
  }
if (isset($error_message)) {
  echo '<p style="color:red">' . $error_message . '</p>';
}
