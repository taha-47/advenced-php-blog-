<?php
/*
==============================
* Comments page
==============================
*/
ob_start();
session_start();
$pageTitle = 'Comments';

if (isset($_SESSION['username'])) {
  include 'init.php';

  /* Get all comments */
  $stmtCom = $conn->prepare("SELECT comments.*, users.Username , posts.PostTitle FROM comments INNER JOIN users ON users.UserID = C_author INNER JOIN posts ON posts.PostID = C_post ORDER BY C_ID DESC");
  $stmtCom->execute();
  $comts = $stmtCom->fetchAll();

  $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; ?>

  <div class="content">
    <?php
    /*--------Manage page--------*/
    if ($do == 'manage') { ?>

      <h2>Comments</h2>
      <section class="manage manamge-comments">
        <div class="panel panel-primary">
          <div class="panel-heading"><i class="fa fa-comments"></i>comments</div>
          <div class="panel-body">
            <div class="tabel-responsive">
              <table class="text-center table">
                <tr>
                  <td>Author</td>
                  <td>Comment</td>
                  <td>Response to</td>
                  <td>Submitted on</td>
                </tr>
                <?php
                if (empty($comts)) {
                  echo '<tr><td>No comments found.</td></tr>';
                } else {
                  foreach ($comts as $comt) {
                    echo
                      '<div class="modal fade" id="myModal' . $comt['C_ID'] . '" tabindex="-1" role="alert" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                      <div class="modal-content modal-alert">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Delete alert</h4>
                        </div>
                        <div class="modal-body text-center">
                          <p>Are you sure that you want to <strong>delete</strong> this comment ?</p>
                        </div>
                        <div class="modal-footer">
                          <a href="?do=delete&comid=' . $comt["C_ID"] . '" class="btn btn-danger">Yes, delete</a>
                          <a class="btn btn-default" data-dismiss="modal">Close</a>
                        </div>
                        </div>
                      </div>
                    </div>
                    
                    <tr class="display-item">
                      <td>' . $comt['Username'] . '
                        <div class="hidden-btn">';
                    if ($comt['C_status'] == 0) {
                      echo '<a href="?do=approve&comid=' . $comt['C_ID'] . '" class="btn btn-warning">Approve<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    }
                    echo ' 
                          <a href="?do=edit&comid=' . $comt['C_ID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                          <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $comt['C_ID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        </div>
                      </td>
                      <td>' . $comt['Comment'] . '</td>
                      <td><a href="posts.php?do=edit&postid=' . $comt['C_post'] . '">' . $comt['PostTitle'] . '</a></td>
                      <td>' . $comt['C_date'] . '</td>
                    </tr>';
                  }
                }
                ?>
              </table>
            </div>
          </div>
        </div>
  </div>
  </div>
  </section>

<?php
      /*--------Edit page--------*/
    } elseif ($do == 'edit') {

      $comid = isset($_GET['comid']) || is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      /* Get the current commment */
      $stmtCom = $conn->prepare("SELECT comments.*, users.Username FROM comments INNER JOIN users ON users.UserID = C_author WHERE C_ID = ? ORDER BY C_ID DESC");
      $stmtCom->execute(array($comid));
      $comt = $stmtCom->fetch();
?>
  <section>
    <h2>Edit comment</h2>
    <form action="?do=update" method="post" class="form-horizontal">
      <input type="hidden" name="c_id" value="<?php echo $comid; ?>">

      <div class="form-group">
        <label for="comment" class="col-sm-2 control-label">Comments</label>
        <div class="col-sm-10">
          <textarea name="comment" id="comment" class="form-control" rows="5"><?php echo $comt['Comment']; ?></textarea>
        </div>
      </div>

      <div class="form-group">
        <label for="status" class="col-sm-2 control-label">Comment status</label>
        <div class="col-sm-10">
          <select class="form-control" name="status" id="status">
            <option value="0" <?php if ($comt['C_status'] == 0) {
                                echo 'selected';
                              } ?>>Unapprove</option>
            <option value="1" <?php if ($comt['C_status'] == 1) {
                                echo 'selected';
                              } ?>>Approve</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>

    </form>
  </section>

<?php
    } elseif ($do == 'approve') {

      $comid = isset($_GET['comid']) || is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      $stmt = $conn->prepare(" UPDATE comments SET C_status = 1 WHERE C_ID= ?");
      $stmt->execute(array($comid));

      /* redirect function */
      $theMsg = '<div class="alert alert-success">Comment was approved successfuly</div>';
      redirectBack($theMsg, 'back');

      /*--------Update page--------*/
    } elseif ($do == 'update') {
      echo '<h3>Update post</h3>';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $com_id       = $_POST['c_id'];
        $com_comment  = $_POST['comment'];
        $com_status   = $_POST['status'];

        /* Update the database with the new values*/

        $stmt = $conn->prepare("UPDATE comments SET Comment = ?, C_status = ? WHERE C_ID = ?");
        $stmt->execute(array($com_comment, $com_status, $com_id));

        $theMsg = '<div class="alert alert-success">the information was updated with success</div>';
        redirectBack($theMsg, 'back');
      } else {
        $errorMsg = '<div class="alert alert-danger">You must set the name of the post</div>';
        redirectBack($errorMsg, 'back');
      }

      /*--------Delete page--------*/
    } elseif ($do == 'delete') {

      $comid = isset($_GET['comid']) || is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

      $stmt = $conn->prepare("DELETE FROM comments WHERE C_ID = ?");
      $stmt->execute(array($comid));

      $theMsg = '<div class="alert alert-success">Comment was deleted successfuly</div>';
      redirectBack($theMsg, 'back');
    }
?>
</div>
<?php
  include $tmplt . "footer.php";
} else {
  header('Location: index.php');
  exit();
}

ob_end_flush();
?>