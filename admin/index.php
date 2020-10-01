<?php

/*
==============================
Login page 
==============================
*/
session_start();
session_regenerate_id();
$pageTitle = 'Signin';
$noSidearea = '';

if (isset($_SESSION['username'])) {
  header('Location: dashboard.php');
}
include 'init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['user'];
  $password = $_POST['pass'];
  $hashPass = sha1($password);

  /*Check the username in the database*/

  $stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ?");
  $stmt->execute(array($username, $hashPass));
  $row = $stmt->fetch();
  $count = $stmt->rowCount();

  if ($count > 0) {
    if (isset($_POST['remember'])) {
      setcookie('member_user', $username, time() + 3600 * 24 * 360, '/');
      setcookie('member_pass', $password, time() + 3600 * 24 * 360, '/');
      $_SESSION['ID'] = $row['UserID'];
      $_SESSION['username'] = $row['Username'];
      header('Location: dashboard.php');
      exit();
    } else {
      $_SESSION['ID'] = $row['UserID'];
      $_SESSION['username'] = $row['Username'];
      header('Location: dashboard.php');
      setcookie('member_user', $username, time() - 3600 * 24 * 360, '/');
      setcookie('member_pass', $password, time() - 3600 * 24 * 360, '/');
    }
  } else {
    $error = 'Wrong username or password :(';
  }
}

?>

<!-- Start Login form -->
<div class="login-page">
  <div class="login-form">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login form-group" method="post">
      <h3 class="text-center">Welcome to admin login</h3>
      <div class="form-group">
        <label><i class="fa fa-user" aria-hidden="true"></i>Username:</label>
        <input type="text" name="user" value="<?php if (isset($_COOKIE['member_user'])) {
                                                echo $_COOKIE['member_user'];
                                              }  ?>" class="form-control" required="required" />
      </div>
      <div class="form-group">
        <label><i class="fa fa-unlock-alt" aria-hidden="true"></i>Password:</label>
        <input type="password" name="pass" value="<?php if (isset($_COOKIE['member_pass'])) {
                                                    echo $_COOKIE['member_pass'];
                                                  }  ?>" class="form-control" />
      </div>
      <div class="form-group">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="remember" <?php if (isset($_COOKIE['member_user'])) {
                                                      echo 'checked="cheched"';
                                                    } ?>> Remember me
          </label>
        </div>
      </div>

      <input type="submit" value="Login" name="login" class="btn btn-primary btn-block" />
    </form>
    <?php if (isset($error)) {
      echo '<div class="alert alert-danger">' . $error . '</div>';
    } ?>
  </div>
</div>

<?php include $tmplt . 'footer.php'; ?>