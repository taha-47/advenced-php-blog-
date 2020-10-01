<?php
/*
================================
 Admin Navbar
===============================
*/

/* Get the user information*/

$userid = $_SESSION['ID'];
$stmt = $conn->prepare("SELECT * FROM users WHERE UserID= ? LIMIT 1");
$stmt->execute(array($userid));
$user = $stmt->fetch();
?>

<div class="content-page">
  <div class="container-fluid">
    <div class="admin-nav">
      <h3 class="pull-left">Admin panel</h3>
      <div class="nav-profile">
        <a href="#" data-toggle="dropdown">
          <?php
          if (!empty($user['UserImg'])) {
            echo '<img src="uploads/user-profile/' . $user['UserImg'] . '" class="profile-pic">';
          } else {
            echo '<img src="asset/images/userpic.png" class="profile-pic">';
          }
          ?>
          <div class="c">
            <p class="profile-name"><?php echo $user['Username']; ?></p>
            <p class="profile-role"><?php echo $user['UserRole']; ?></p>
          </div>

        </a>
        <ul class="dropdown-menu dropdown-profile">
          <li><a href="users.php?do=edit-profile&userid=<?php echo $_SESSION['ID']; ?>"><i class="fa fa-user-o" aria-hidden="true"></i>My account</a></li>
          <li><a href="options.php"><i class="fa fa-cog" aria-hidden="true"></i>Settings</a></li>
          <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
        </ul>
      </div>
      <div class="admin-notif">
        <a href="#" data-toggle="dropdown">
          <i class="fa fa-bell-o" aria-hidden="true"></i>
          <div class="boll"></div>
        </a>
        <ul class="list-group dropdown-menu notifications">
          <li class="list-group-item">Cras justo odio</li>
          <li class="list-group-item">Dapibus ac facilisis in</li>
          <li class="list-group-item">Morbi leo risus</li>
          <li class="list-group-item">Porta ac consectetur ac</li>
          <li class="list-group-item">Vestibulum at eros</li>
        </ul>
      </div>
    </div>