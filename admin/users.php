<?php
/*
==============================
* Users page
==============================
*/
ob_start();
session_start();
$pageTitle = 'User profile';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get all users from database*/
  $stmtUser = $conn->prepare("SELECT * FROM users");
  $stmtUser->execute();
  $users = $stmtUser->fetchAll();

  $do = isset ($_GET['do']) ? $_GET['do'] : 'manage'; ?>

<div class="content">
  <?php
  /*--------Manage page--------*/
  if($do == 'manage'){ ?>

    <h2>Users</h2>
    <section class="manage user-page">
      <div class="btn-add">
        <a href="?do=add" class="btn btn-primary btn-sm">Add user<i class="fa fa-plus-square" aria-hidden="true"></i></a>
      </div>
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-user-o"></i>Manage users</div>
          <div class="panel-body">
            <div class="tabel-responsive">
              <table class="text-center table">
                <tr>
                  <td>Username</td>
                  <td>Name</td>
                  <td>Email</td>
                  <td>Role</td>
                  <td>Posts</td>
                </tr>
                <?php
            foreach ($users as $user) {
              echo'<div class="modal fade" id="myModal'. $user['UserID'] .'" tabindex="-1" role="alert" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content modal-alert">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete alert</h4>
                  </div>
                  <div class="modal-body text-center">
                    <p>Are you sure that you want to <strong>delete</strong> this user ?</p>
                  </div>
                  <div class="modal-footer">
                    <a href="?do=delete&userid='. $user["UserID"].'" class="btn btn-danger">Yes, delete</a>
                    <a class="btn btn-default" data-dismiss="modal">Close</a>
                  </div>
                  </div>
                </div>
              </div>';
              echo '<tr class="display-item">';
              if(!empty($user['UserImg'])){
                echo '
                <td>
                  <a href="?do=edit-profile&userid=' . $user['UserID'] .'">
                  <img src="uploads/user-profile/' . $user['UserImg'].'">' . $user['Username'].'</a>
                    <div class="hidden-btn">
                    <a href="?do=edit-profile&userid='. $user['UserID'] .'" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
                    if($user['UserID'] !== $_SESSION['ID']){
                        echo '<a class="btn btn-danger" data-toggle="modal" data-target="#myModal' .$user['UserID']. '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                    }
                     echo'
                    </div>
                </td>';
               
              }else {
                echo 
                '<td>
                  <a href="?do=edit-profile&userid=' . $user['UserID'] .'"><img src="asset/images/userpic.png">'. $user['Username'] .'</a>
                  <div class="hidden-btn">
                  <a href="?do=edit-profile&userid='. $user['UserID'] .'" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
                  if($user['UserID'] !== $_SESSION['ID']){
                      echo '<a class="btn btn-danger" data-toggle="modal" data-target="#myModal' .$user['UserID']. '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                  }
              echo'
                  </div>
                </td>';
              }
                echo 
                 '<td>' . $user['Fullname'] . '</td>
                  <td>' . $user['Email'] . '</td>
                  <td>' . ucfirst($user['UserRole']) . '</td>
                  <td>' . $user['Fullname'] . '</td>
                </tr>';
            }
            ?>
              </div>
            </table>
          </div>
        </div>
      </div>
    </section>
  <?php
  /*--------Add page--------*/
}elseif ($do == 'add') { ?>

<section class="seclect-cat">
  <h2>Add new post</h2>
  <div class="row">
    <div class="col-md-8">
      <form class="form-horizontal" action="?do=insert" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="username" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="username" name="username" required="required">
          </div>
        </div>
        <div class="form-group">
          <label for="userEmail" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="userEmail" name="userEmail" required="required">  
          </div>
        </div>
        <div class="form-group">
          <label for="fullname" class="col-sm-2 control-label">Fullname</label>
          <div class="col-sm-10">
            <input type="text" name="fullname" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">Password</label>
          <div class="col-sm-10">
            <input type="password" name="password" class="form-control" required="required">
          </div>
        </div>
        <div class="form-group">
          <label for="postTitle" class="col-sm-2 control-label">User picture</label>
          <div class="col-sm-10">
            <input type="file" name="userPic" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label for="postTitle" class="col-sm-2 control-label">Role</label>
          <div class="col-sm-3">
            <select class="form-control" name="userRole">
              <option value="author">Author</option>
              <option value="editor">Editor</option>
              <option value="administrator">Administrator</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-info">Add user</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  </section>

  <?php
  /*--------Insert page--------*/
}elseif ($do == 'insert') {

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $user     = $_POST['username'];
    $email    = $_POST['userEmail'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $hashPass = sha1($password);
    $userRole = $_POST['userRole'];

    /* Set the post image information
    $userPic    = $_FILES['userPic']['name'];
    $tmp_dir    = $_FILES['userPic']['tmp_name'];
    $imageSize  = $_FILES['userPic']['size'];
    $upload_dir = 'uploads/user-profile';
    $imageExt   = strtolower(pathinfo($userPic, PATHINFO_EXTENSION));
    $validExt   = array('jpg', 'jpeg', 'png');
    (!empty($imageExt)) ? $picProfile = 'prifile' . rand(10, 100000) . '.' . $imageExt : $picProfile ='';    
    move_uploaded_file($tmp_dir, $upload_dir . $picProfile);*/
    $picture    = image_uploader('userPic', 'uploads/user-profile/');

    // check if the field is empty
    $formErrors = array();

    if(empty($user)){
      $formErrors[] = 'You must set the <strong>username</strong>';
    }
    if(empty($email)){
      $formErrors[] = 'You must set the <strong>email</strong>';
    }
    if(empty($password)){
      $formErrors[] = 'You must set the <strong>password</strong>';
    }

    if(empty($formErrors)){

      $cheked = checkItem('Username', 'users', $user);
      if($cheked == 1){

        /* redirect function */
        $theMsg = '<div class="alert alert-warning">Usename is already exist try with an other name </div>';
        redirectBack($theMsg, 'back');
      }else{
  
        /* insert values to the database*/
        $stmt = $conn->prepare("INSERT INTO users (Username, Password, Fullname, Email, UserImg, UserRole) VALUES (:username, :pass, :fullname, :email, :userPic, :userrole)");
        $stmt->execute(array(
          'username' => $user,
          'pass'     => $hashPass,
          'fullname' => $fullname,
          'email'    => $email,
          'userPic'  => $picture,
          'userrole' => $userRole
        ));

        /*Success message*/
        $theMsg = '<div class="alert alert-success">the article was added with success</div>';
        redirectBack($theMsg, 'back');
      }
    }else{
      foreach($formErrors as $error){
        echo '<div class="alert alert-danger">' . $error . '</div>';
      }
    }
      
  }else{
    $theMsg = '<div class="alert alert-danger">You can\'t browse this page directly</div>';
    redirectBack($theMsg);
  }
  /*--------Edit page--------*/
}elseif ($do == 'edit-profile') { 
  
 /*Get the user information*/
 
 $userid = isset($_GET['userid']) || is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

 $stmt = $conn->prepare("SELECT * FROM users WHERE UserID= ? LIMIT 1");
 $stmt->execute(array($userid));
 $user = $stmt->fetch();
 $count = $stmt->rowCount();

if($count > 0){?>
  <section class="seclect-cat">
  <h2>Edit profile</h2>
  <div class="row">
    <div class="col-md-8">
      <form class="form-horizontal" action="?do=update" method="post" enctype="multipart/form-data">
      <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <div class="form-group">
          <label for="username" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['Username']; ?>" required="required" disabled="disabled">
            <small>Usernames cannot be changed.</small>
          </div>
        </div>
        <div class="form-group">
          <label for="userEmail" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="userEmail" name="userEmail" value="<?php echo $user['Email']; ?>" required="required">  
          </div>
        </div>
        <div class="form-group">
          <label for="fullname" class="col-sm-2 control-label">Fullname</label>
          <div class="col-sm-10">
            <input type="text" name="fullname" class="form-control"  value="<?php echo $user['Fullname']; ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">Password</label>
          <div class="col-sm-10">
            <input type="password" name="newpassword" class="form-control">
            <input type="hidden" name="oldpassword" value="<?php echo $user['Password']?>"/>
          </div>
        </div>
        <div class="form-group">
          <label for="userEmail" class="col-sm-2 control-label">Biographical Info</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="biographical" rows="5" maxlength="500"><?php echo $user['Biographical']; ?></textarea>
          </div>
        </div>
        <div class="form-group">
              <label for="postTitle" class="col-sm-2 control-label">User picture</label>
              <div class="col-sm-10">
                <input type="file" name="userPic" class="form-control">
                <input type="hidden" name="olduserPic" class="form-control" value="<?php echo $user['UserImg']; ?>">
              </div>
            </div>
        <div class="form-group">
          <label for="postTitle" class="col-sm-2 control-label">Role</label>
          <div class="col-sm-3">
            <select class="form-control" name="userRole">
              <option value="author" <?php if ($user['UserRole'] == 'author'){ echo 'selected';}?>>Author</option>
              <option value="editor" <?php if ($user['UserRole'] == 'editor'){ echo 'selected';}?>>Editor</option>
              <option value="administrator" <?php if ($user['UserRole'] == 'administrator'){ echo 'selected';}?>>Administrator</option>
              </select>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Edit Profile</button>
          </div>
        </div>
      </form>
    </div>
    <!-- User profile card-->
    <div class="col-md-4">
      <div class="user-profile-card text-center">

        <?php 
        if(!empty($user['UserImg'])){
          echo '<img src="uploads/user-profile/'. $user['UserImg'] . '" class="profile-pic">';
        }else{
          echo '<img src="asset/images/userpic.png" class="profile-pic">';
        }
        
        ?>
        <h3><?php echo $user['Fullname'];?></h3>
        <p><?php echo ucfirst($user['UserRole']); ?></p>
        <p><i class="fa fa-envelope-o" aria-hidden="true"></i><?php echo $user['Email'];?></p>
        <?php
          if(!empty($user['Biographical'])){
            echo '<p class="text-left"><i class="fa fa-quote-left" aria-hidden="true"></i>' . $user['Biographical']. '<i class="fa fa-quote-right" aria-hidden="true"></i></p>';
          }
            
        ?>
      </div>
    </div>
  </div>
  </section>
<?php
}
  /*--------Update page--------*/
}elseif ($do == 'update') {

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $userid     = $_POST['userid'];
    $email      = $_POST['userEmail'];
    $fullname   = $_POST['fullname'];
    $bioInfo    = $_POST['biographical'];
    $password   = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
    $userRole   = $_POST['userRole'];

    $picture    = !empty($_FILES['userPic']['name']) ? image_uploader('userPic', 'uploads/user-profile/') : $_POST['olduserPic'];

    // check if the field is empty

    if(!empty($email)){
      /* insert info to the database*/
      $stmt = $conn->prepare("UPDATE users SET Fullname = ? , biographical = ?, Email = ?, Password = ? , UserRole = ?, UserImg = ? WHERE UserID = ?");
      $stmt->execute(array($fullname,  $bioInfo, $email, $password, $userRole, $picture, $userid)); 

      /* success message */
      $theMsg = '<div class="alert alert-success">the information was updated with success</div>';
      redirectBack($theMsg, 'back');
    }else{
      $theMsg = '<div class="alert alert-danger">You must set ana email</div>';
       redirectBack($theMsg, 'back');
    }
  }else{
    $theMsg = '<div class="alert alert-danger">You can\'t browse this page directly</div>';
    redirectBack($theMsg);
  }
/*--------Delete page--------*/
}elseif ($do == 'delete') {

  $userid = isset($_GET['userid']) || is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

  $check = checkItem('UserID', 'users', $userid);

  if($check > 0){
    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->execute(array($userid));
  
    $theMsg = '<div class="alert alert-success">User was deleted successfuly</div>';
    redirectBack($theMsg, 'back');
  }else{
    /* redirect function */
    $theMsg = '<div class="alert alert-warning">You try to delete unexist user</div>';
    redirectBack($theMsg, 'back');
  }
  
}
?>
</div>
<?php
  include $tmplt . "footer.php";
}else {
  header('Location: index.php');
  exit();
}
ob_end_flush();
?>
