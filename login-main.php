
<?php
 // Start the session
  session_start();
 // Clear the error message
  $error_msg = "";
// If the user isn't logged in, try to log them in
  if (!isset($_SESSION['usermain'])) {
    if (isset($_POST['submit'])) {
      // Connect to the database
      $dbc = mysqli_connect('localhost','root','','healthcare');

      // Grab the user-entered log-in data
      $user_username = mysqli_real_escape_string($dbc, trim($_POST['mainid']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['mainpass']));

      if (!empty($user_username) && !empty($user_password)) {
        // Look up the username and password in the database
        $query = "SELECT usermain FROM user_main WHERE usermain = '$user_username' AND password = '$user_password'";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array($data);
          $_SESSION['usermain'] = $row['usermain'];
          setcookie('usermain', $row['usermain'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/mainhospital.php';
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Sorry, you must enter a valid username and password to log in.';
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Sorry, you must enter your username and password to log in.';
      }
    }
  }
  else{
	  echo "session is created";
  }
?>

<html>
<head>
	<title>Main page</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

</head>
<body>
<?php
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if (empty($_SESSION['usermain'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>

<div class="row">
<div class="col-md-offset-3 col-md-6">
<form role=" form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div class="form-group">
    <label for="login"><h4>Login-Main</h4></label>
   
  </div>
  <div class="form-group">
    <label for="mainid">Username</label><input type="text" class="form-control" name="mainid"  id="mainid">
     <label for="mainpass">Password</label><input type="password" class="form-control" name="mainpass" id="mainpass">
  
  </div>
  <div>
    
   <button type="submit" name="submit" class="btn btn-primary">Login</button>
     
  
  </div>
 
</form>
</div>
</div>

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<p class="login">You are logged in as ' . $_SESSION['usermain'] . '.</p>');
  }
?>
</body>
</html>

