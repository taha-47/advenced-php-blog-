<?php
/*
===========================
Profile page 
===========================
*/

session_start();

$pageTitle = 'Profile';

include 'init.php';

$profileid = isset($_GET['id']) || is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


/*select the currant id from database*/
$stmt = $conn->prepare("SELECT posts.*, users.Username FROM posts  INNER JOIN users ON users.UserID = PostAuthor  WHERE PostID = ? ORDER BY PostID DESC LIMIT 1");
$stmt->execute(array($profileid));
$post = $stmt->fetch();
$count = $stmt->rowCount();

/*Get all users from database*/
$stmtUser = $conn->prepare("SELECT * FROM users");
$stmtUser->execute();
$user = $stmtUser->fetch();
$count = $stmtUser->rowCount();

?>
<div class="">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <?php
        if (isset($profileid) && $profileid > 0) { ?>
          <section class="user-profile">
            <div class="user-info">
              <?php
              if ($count > 0) {
                if (!empty($user['UserImg'])) {
                  echo '<img src="admin/uploads/user-profile/' . $user['UserImg'] . '"/>';
                } else {
                  echo '<img src="asset/images/userpic.png" />';
                }
                echo
                  '<div class="info">
                      <h3 class="author-name">' . $user['Fullname'] . '</h3>
                      <span class="author-role">' . $user['UserRole'] . '</span>
                      <p class="author-bio">' . $user['Biographical'] . '</p>
                      <div class="author-social">
                      <a href="#">
                        <i class="fa fa-facebook-square" aria-hidden="true"></i>
                      </a>
                      <a href="#">
                        <i class="fa fa-twitter-square" aria-hidden="true"></i>
                      </a>
                      <a href="#">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                      </a>
                      </div>
                    </div>';
              }
              ?>
            </div>
          </section>
        <?php
        } else {
          echo '<div class="alert alert-danger">there is no user with this id</div></div>';
        } ?>
      </div>
      <!--Sidebar area-->
      <div class="col-md-4">
        <?php include $tmplt . 'sidebar.php'; ?>
      </div>
    </div>

  </div>
</div>
</div>



<?php
include $tmplt . 'footer.php';
?>