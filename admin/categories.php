<?php
/*
==============================
 Categories page
==============================
*/
ob_start();
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get all categories*/
  $stmtCat = $conn->prepare("SELECT * FROM categories");
  $stmtCat->execute();
  $cats = $stmtCat->fetchAll();

  $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; ?>

  <div class="content">
    <?php
    /*--------Manage page--------*/
    if ($do == 'manage') { ?>

      <h2>Categories</h2>
      <section class="manage">
        <div class="row">
          <div class="col-md-8">
            <div class="panel panel-primary">
              <div class="panel-heading"><i class="fa fa-list-ul"></i> Manage Categories</div>
              <div class="panel-body">
                <div class="tabel-responsive">
                  <table class="text-center table">
                    <tr>
                      <td>Name</td>
                      <td>Description</td>
                    </tr>
                    <?php
                    if (empty($cats)) {
                      echo '<div class="alert alert-warning">there is no categories</div>';
                    } else {
                      foreach ($cats as $cat) {
                        echo '<div class="modal fade" id="myModal' . $cat['CatID'] . '" tabindex="-1" role="alert" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content modal-alert">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Delete alert</h4>
                      </div>
                      <div class="modal-body text-center">
                        <p>Are you sure that you want to <strong>delete</strong> this category ?</p>
                      </div>
                      <div class="modal-footer">
                        <a href="?do=delete&pageid=' . $cat['CatID'] . '" class="btn btn-danger">Yes, delete</a>
                        <a class="btn btn-default" data-dismiss="modal">Close</a>
                      </div>
                      </div>
                    </div>
                  </div>';

                        if (empty($cat['CatDesc'])) {
                          $cat['CatDesc'] = '—';
                        }
                        echo
                          '<tr class="display-item">
                    <td>
                      <a href="?do=edit&catid=' . $cat['CatID'] . '"">' . $cat['CatName'] . '</a>
                      <div class="hidden-btn">
                        <a href="?do=edit&catid=' . $cat['CatID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $cat['CatID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                      </div>
                    </td>
                    <td>' . $cat['CatDesc'] . '</td>
                  </tr>';
                      }
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 add-cat">
            <div class="panel panel-primary">
              <div class="panel-heading"><i class="fa fa-list-ul"></i> Add Categories</div>
              <div class="panel-body">
                <form method="post" action="?do=insert">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="catName" class="form-control" id="name">
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="catDesc" class="form-control" id="description" rows="3"></textarea>
                  </div>
                  <button type="submit" class="btn btn-success">Add</button>
                </form>
              </div>
            </div>
          </div>
        </div>
  </div>
  </section>
  <?php

      /*--------Insert page--------*/
    } elseif ($do == 'insert') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $catName = $_POST['catName'];
        $catDesc = $_POST['catDesc'];

        $check =  checkItem('CatName', 'categories', $catName);

        if (!empty($catName)) {

          if ($check == 1) {
            /* redirect function */
            $theMsg = '<div class="alert alert-danger">Category is already set try with an other name </div>';
            redirectBack($theMsg, 'back');
          } else {
            $stmt = $conn->prepare("INSERT INTO Categories (CatName, CatDesc) VALUES (:name , :description)");
            $stmt->execute(array(
              'name'        => $catName,
              'description' => $catDesc
            ));

            /*Success message*/
            $theMsg = '<div class="alert alert-success">the information was added with success</div>';
            redirectBack($theMsg, 'back');
          }
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the name of category</div>';
          redirectBack($errorMsg, 'back');
        }
      }
      /*--------Edit page--------*/
    } elseif ($do == 'edit') {

      $catid = isset($_GET['catid']) || is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

      /*select the currant id from database*/
      $stmt = $conn->prepare("SELECT * From categories WHERE CatID = ? LIMIT 1");
      $stmt->execute(array($catid));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();

      /*Check if the id is exist in the database*/
      if ($count > 0) {
  ?>
    <section class="add-cat">
      <h2>Edit category</h2>
      <div>
        <form action="?do=update" method="post">
          <input type="hidden" name="catid" value="<?php echo $row['CatID'] ?>" />
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-8">
              <input type="text" name="catName" class="form-control" id="name" value="<?php echo $row['CatName']; ?>" />
            </div>
          </div>
          <div class="form-group row">
            <label for="description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-8">
              <textarea class="form-control" id="description" name="catDesc" rows="3"><?php echo $row['CatDesc']; ?></textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-8">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>
        </form>
      </div>
    </section>
<?php
      } else {
        $errorMsg = '<div class="alert alert-warning">This category is not exist</div>';
        redirectBack($errorMsg);
      }

      /*--------Update page--------*/
    } elseif ($do == 'update') {

      echo '<h3>Update category</h3>';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $catid   = $_POST['catid'];
        $catName = $_POST['catName'];
        $catDesc = $_POST['catDesc'];

        /*Ckeck if the name is already exist*/
        $check =  checkItem('CatName', 'categories', $catName);

        /*Chek if the name is empty*/
        if (!empty($catName)) {
          if ($check == 1) {
            /* redirect function */
            $theMsg = '<div class="alert alert-danger">Category is already set try with an other name </div>';
            redirectBack($theMsg, 'back');
          } else {
            /*Update the database with the new info*/
            $stmt = $conn->prepare("UPDATE categories SET CatName = ? , CatDesc = ? WHERE CatID = ?");
            $stmt->execute(array($catName, $catDesc, $catid));

            $theMsg = '<div class="alert alert-success">the information was updated with success</div>';
            redirectBack($theMsg, 'back');
          }
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the name of category</div>';
          redirectBack($errorMsg, 'back');
        }
      }

      /*--------Delete page--------*/
    } elseif ($do == 'delete') {

      $catid = isset($_GET['catid']) || is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

      $stmt = $conn->prepare("DELETE  FROM categories WHERE CatID = ?");
      $stmt->execute(array($catid));

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