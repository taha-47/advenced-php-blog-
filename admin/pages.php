<?php
/*
==============================
* Posts page
==============================
*/

ob_start();
session_start();
$pageTitle = 'Pages';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get all pages*/
  $stmtPage = $conn->prepare("SELECT pages.*, users.Username FROM pages INNER JOIN users ON users.UserID = PageAuthor ORDER BY PageID DESC");
  $stmtPage->execute();
  $pages = $stmtPage->fetchAll();

  /*Get all user*/
  $stmtUser = $conn->prepare("SELECT * FROM users");
  $stmtUser->execute();
  $users = $stmtUser->fetchAll();

  $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; ?>

  <div class="content">
    <?php
    /*--------Manage page--------*/
    if ($do == 'manage') { ?>
      <h2>Pages</h2>
      <section class="manage">
        <div class="btn-add">
          <a href="?do=add" class="btn btn-primary btn-sm">Add new<i class="fa fa-plus-square" aria-hidden="true"></i></a>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading"><i class="fa fa-file-text-o"></i>Manage pages</div>
          <div class="panel-body">
            <div class="tabel-responsive">
              <table class="text-center table">
                <tr>
                  <td>Page image</td>
                  <td>Page Title</td>
                  <td>Page Author</td>
                  <td>Published</td>
                </tr>
                <?php
                if (empty($pages)) {
                  echo '<div class="alert alert-warning">there is no pages</div>';
                } else {
                  foreach ($pages as $page) {
                    echo '<div class="modal fade" id="myModal' . $page['PageID'] . '" tabindex="-1" role="alert" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content modal-alert">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Delete alert</h4>
                        </div>
                        <div class="modal-body text-center">
                          <p>Are you sure that you want to <strong>delete</strong> this page ?</p>
                        </div>
                        <div class="modal-footer">
                          <a href="?do=delete&pageid=' . $page["PageID"] . '" class="btn btn-danger">Yes, delete</a>
                          <a class="btn btn-default" data-dismiss="modal">Close</a>
                        </div>
                        </div>
                      </div>
                    </div>';

                    if (empty($page['PageTitle'])) {
                      $page['PageTitle'] = '—';
                    } else {
                      echo
                        '<tr class="display-item">';
                      if (!empty($page['PageImg'])) {
                        echo '
                        <td class="item-img">
                          <img src="uploads/' . $page['PageImg'] . '" class="img-responsive" >
                          <div class="hidden-btn">
                            <a href="?do=edit&pageid=' . $page['PageID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $page['PageID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                          </div>
                        </td>';
                      } else {
                        echo '
                        <td class="item-img">
                          <img src="asset/images/no-img.png" class="img-responsive" >
                          <div class="hidden-btn">
                            <a href="?do=edit&pageid=' . $page['PageID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $page['PageID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                          </div>
                        </td>';
                      }
                      echo '
                        <td> <a href="?do=edit&pageid=' . $page['PageID'] . '">' . $page['PageTitle'] . '</a> </td>
                        <td>' . $page['Username'] . '</td>
                        <td>' . $page['PageDate'] . '</td>
                    </tr>';
                    }
                  }
                }
                ?>
              </table>
            </div>
          </div>
        </div>
      </section>
    <?php

      /*--------Add page--------*/
    } elseif ($do == 'add') { ?>
      <section class="seclect-cat">
        <h2>Add new post</h2>
        <div class="row">
          <div class="col-md-10">
            <form class="form-horizontal" action="?do=insert" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="pageTitle" class="col-sm-2 control-label">Page title</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="pageTitle" name="pageTitle">
                </div>
              </div>
              <div class="form-group">
                <label for="postContent" class="col-sm-2 control-label">Page</label>
                <div class="col-sm-10">
                  <textarea class="fr-view" id="editor" name="pageContent"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="pageTitle" class="col-sm-2 control-label">Choose picture</label>
                <div class="col-sm-10">
                  <input type="file" name="pagePic" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="pageTitle" class="col-sm-2 control-label">Author</label>
                <div class="col-sm-2">
                  <select class="form-control" name="pageAuthor">
                    <?php
                    foreach ($users as $user) {
                      echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-success">Add page</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
      <?php
      /*--------Insert page--------*/
    } elseif ($do == 'insert') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $title      = $_POST['pageTitle'];
        $content    = $_POST['pageContent'];
        $author     = $_POST['pageAuthor'];
        $picture    = image_uploader('pagePic', 'uploads/');

        if (!empty($title)) {
          $stmt = $conn->prepare("INSERT INTO pages (PageTitle, PageContent, PageAuthor, PageImg, PageDate) VALUES (:title , :content, :author, :image, now())");
          $stmt->execute(array(
            'title'    => $title,
            'content'  => $content,
            'author'   => $author,
            'image'    => $picture
          ));

          /*Success message*/
          $theMsg = '<div class="alert alert-success">the article was added with success</div>';
          redirectBack($theMsg, 'back');
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the name of category</div>';
          redirectBack($errorMsg, 'back');
        }
      } else {
        $theMsg = '<div class="alert alert-danger">You can\'t show this page directly</div>';
        redirectBack($theMsg);
      }
      /*--------Edit page--------*/
    } elseif ($do == 'edit') {

      $pageid = isset($_GET['pageid']) || is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;

      /*select the currant id from database*/
      $stmt = $conn->prepare("SELECT * From pages WHERE PageID = ? LIMIT 1");
      $stmt->execute(array($pageid));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();

      /*Check if the ID is exist in the database*/
      if ($count > 0) {
      ?>
        <section class="seclect-cat">
          <h2>Edit page</h2>
          <div class="row">
            <div class="col-md-9">
              <form class="form-horizontal" action="?do=update" method="post" enctype="multipart/form-data">
                <input type="hidden" name="pageid" value="<?php echo $pageid; ?>">
                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Page title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="pageTitle" name="pageTitle" value="<?php echo $row['PageTitle'] ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="postContent" class="col-sm-2 control-label">page</label>
                  <div class="col-sm-10">

                    <textarea class="fr-view" id="editor" name="pageContent"><?php echo $row['PageContent'] ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Page picture</label>
                  <div class="col-sm-10">
                    <input type="file" name="pagePic" class="form-control">
                    <input type="hidden" name="oldPagePic" value="<?php echo $row['PageImg']; ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Author</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="pageAuthor">
                      <?php
                      foreach ($users as $user) {
                        echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>
    <?php
      } else {
        $errorMsg = '<div class="alert alert-warning">This category is not exist</div>';
        redirectBack($errorMsg);
      }

      /*--------Update page--------*/
    } elseif ($do == 'update') {

      echo '<h3>Update page</h3>';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $pageid     = $_POST['pageid'];
        $title      = $_POST['pageTitle'];
        $content    = $_POST['pageContent'];
        $author     = $_POST['pageAuthor'];
        //$picture    = image_uploader('image', 'uploads/');
        $picture    = !empty($_FILES['pagePic']['name']) ? image_uploader('pagePic', 'uploads/') : $_POST['oldPagePic'];
        /*Chek if the name is empty*/
        if (!empty($title)) {

          /*Update the database with the new info*/
          $stmt = $conn->prepare("UPDATE pages SET PageTitle = ? , PageContent = ?, PageAuthor = ?, PageImg = ? WHERE PageID = ?");
          $stmt->execute(array($title, $content, $author, $picture, $pageid));

          $theMsg = '<div class="alert alert-success">the information was updated with success</div>';
          redirectBack($theMsg, 'back');
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the name of the post</div>';
          redirectBack($errorMsg, 'back');
        }
      }
      /*--------Delete page--------*/
    } elseif ($do == 'delete') {

      $pageid = isset($_GET['pageid']) || is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;

      $stmt = $conn->prepare("DELETE FROM pages WHERE PageID = ?");
      $stmt->execute(array($pageid));

      $theMsg = '<div class="alert alert-success">Category was deleted successfuly</div>';
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