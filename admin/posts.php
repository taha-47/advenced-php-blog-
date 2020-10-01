<?php
/*
==============================
* Posts page
==============================
*/
ob_start();
session_start();
$pageTitle = 'Posts';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get all categories*/
  $stmtCat = $conn->prepare("SELECT * FROM categories");
  $stmtCat->execute();
  $cats = $stmtCat->fetchAll();

  /*Get all user*/
  $stmtUser = $conn->prepare("SELECT * FROM users");
  $stmtUser->execute();
  $users = $stmtUser->fetchAll();

  /*Get all postes*/
  $stmtPost = $conn->prepare("SELECT posts.*, users.Username FROM posts INNER JOIN users ON users.UserID = PostAuthor ORDER BY PostID DESC ");
  $stmtPost->execute();
  $posts = $stmtPost->fetchAll();

  $do = isset($_GET['do']) ? $_GET['do'] : 'manage'; ?>

  <div class="content">
    <?php
    /*--------Manage page--------*/
    if ($do == 'manage') { ?>
      <h2>Posts</h2>
      <section class="manage">
        <div class="btn-add">
          <a href="?do=add" class="btn btn-primary btn-sm">Add new<i class="fa fa-plus-square" aria-hidden="true"></i></a>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading"><i class="fa fa-file-text-o"></i>Manage Posts</div>
          <div class="panel-body">
            <div class="tabel-responsive">
              <table class="table">
                <tr>
                  <td>Post image</td>
                  <td>Post Title</td>
                  <td>Post Author</td>
                  <td>Categories</td>
                  <td>Published</td>
                </tr>
                <?php
                if (empty($posts)) {
                  echo '<tr><td>No comments found.</td></tr>';
                } else {
                  foreach ($posts as $post) {
                    echo '<div class="modal fade" id="myModal' . $post['PostID'] . '" tabindex="-1" role="alert" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content modal-alert">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Delete alert</h4>
                          </div>
                          <div class="modal-body text-center">
                            <p>Are you sure that you want to <strong>delete</strong> this post ?</p>
                          </div>
                          <div class="modal-footer">
                            <a href="?do=delete&postid=' . $post["PostID"] . '" class="btn btn-danger">Yes, delete</a>
                            <a class="btn btn-default" data-dismiss="modal">Close</a>
                          </div>
                          </div>
                        </div>
                      </div>';
                    if (empty($post['PostTitle'])) {
                      $post['PostTitle'] = '—';
                    } else {
                      echo
                        '
                        <tr class="display-item">';
                      if (!empty($post['PostImg'])) {
                        echo '
                            <td class="item-img">
                              <img src="uploads/' . $post['PostImg'] . '" class="img-responsive" />
                                <div class="hidden-btn">
                                  <a href="?do=edit&postid=' . $post['PostID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                  <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $post['PostID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </div>
                            </td>';
                      } else {
                        echo '
                            <td class="item-img">
                              <img src="asset/images/no-img.png" class="img-responsive" >
                              <div class="hidden-btn">
                                <a href="?do=edit&postid=' . $post['PostID'] . '" class="btn btn-info">Edit<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a class="btn btn-danger" data-toggle="modal" data-target="#myModal' . $post['PostID'] . '">Delete<i class="fa fa-trash-o" aria-hidden="true"></i></a>
                              </div>
                            </td>';
                      }
                      echo '
                            <td> <a href="?do=edit&postid=' . $post['PostID'] . '">' . $post['PostTitle'] . '</a> </td>
                            <td>' . $post['Username'] . '</td>
                            <td>' . $post['PostCat']  . '</td>
                            <td>' . $post['PostDate'] . '</td>
                        </tr>';
                    }
                  }
                }
                ?>
              </table>
            </div>
          </div>
        </div>
        <div>
      </section>

    <?php
      /*--------Add page--------*/
    } elseif ($do == 'add') { ?>
      <section class="seclect-cat">
        <h2>Add new post</h2>
        <form class="form-horizontal" action="?do=insert" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-9">
              <div class="form-group">
                <label for="postTitle" class="col-sm-2 control-label">Post title</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="postTitle" name="postTitle">
                </div>
              </div>
              <div class="form-group">
                <label for="postContent" class="col-sm-2 control-label">post</label>
                <div class="col-sm-10">
                  <textarea class="fr-view" id="editor" name="postContent"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="postTitle" class="col-sm-2 control-label">Post thumbnail</label>
                <div class="col-sm-10">
                  <input type="file" name="postPic" class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="postTitle" class="col-sm-2 control-label">Author</label>
                <div class="col-sm-2">
                  <select class="form-control" name="postAuthor">
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
                  <button type="submit" class="btn btn-success add-post">Add post</button>
                </div>
              </div>
            </div>
            <!-- Sidebar-->
            <div class="col-md-3">
              <!--Categories area-->
              <div class="panel panel-primary">
                <div class="panel-heading"><i class="fa fa-list-ul"></i>Categories</div>
                <div class="panel-body cat-select">
                  <?php
                  foreach ($cats as $cat) {
                    echo
                      '<div class="checkbox">
                        <label>
                          <input type="checkbox" value="' . $cat['CatName'] . '" name="category[]" >' . $cat['CatName'] . '
                        </label>
                      </div>';
                  }
                  ?>
                </div>
                <div class="panel-footer">
                  <a class="btn btn-default" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Add new category</a>
                  <div class="collapse" id="collapseExample">
                    <div class="form-group">
                      <label for="add-cat">Name</label>
                      <form action="categories.php?do=insert" method="post">
                        <input type="text" name="catName" class="form-control" id="add-cat" />
                        <input type="hidden" name="catDesc" />
                        <button type="submit" class="btn btn-default">Add</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!--tags area-->
              <div class="tags-area">
                <div class="panel panel-primary">
                  <div class="panel-heading"><i class="fa fa-tags"></i>Tags</div>
                  <div class="panel-body">
                    <input type="text" class="form-control tag-input" />
                    <small><i>Press comma to add tag</i></small>
                    <input type="hidden" class="tags-values" name="PostTag" />
                    <div class="added-tags"></div>
                  </div>
                </div>
              </div>

            </div>
        </form>
      </section>
      <?php
      /*--------Insert page--------*/
    } elseif ($do == 'insert') {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $title      = $_POST['postTitle'];
        $content    = $_POST['postContent'];
        $author     = $_POST['postAuthor'];
        $tag        = $_POST['PostTag'];
        $categories = isset($_POST['category']) ? $_POST['category'] : 'Uncategorised';
        $newcat     = implode(",", (array)$categories);
        $picture    = image_uploader('postPic', 'uploads/');

        if (!empty($title)) {
          $stmt = $conn->prepare("INSERT INTO posts (PostTitle, PostContent, PostAuthor, PostTag, PostCat, PostImg, PostDate) VALUES (:title, :content, :author, :tag, :category, :image, now())");
          $stmt->execute(array(
            'title'    => $title,
            'content'  => $content,
            'author'   => $author,
            'tag'      => $tag,
            'category' => $newcat,
            'image'    => $picture
          ));

          /*Success message*/
          $theMsg = '<div class="alert alert-success">the article was added with success</div>';
          redirectBack($theMsg, 'back');
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the title of this post</div>';
          redirectBack($errorMsg, 'back');
        }
      } else {
        $theMsg = '<div class="alert alert-danger">You can\'t show this page directly</div>';
        redirectBack($theMsg);
      }
      /*--------Edit page--------*/
    } elseif ($do == 'edit') {

      $postid = isset($_GET['postid']) || is_numeric($_GET['postid']) ? intval($_GET['postid']) : 0;

      /*select the currant id from database*/
      $stmt = $conn->prepare("SELECT * From posts WHERE PostID = ? LIMIT 1");
      $stmt->execute(array($postid));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();

      /*Check if the ID is exist in the database*/
      if ($count > 0) {
      ?>
        <section class="seclect-cat">
          <h2>Edit post</h2>
          <form class="form-horizontal" action="?do=update" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-9">
                <input type="hidden" name="postid" value="<?php echo $postid; ?>">
                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Post title</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="postTitle" name="postTitle" value="<?php echo $row['PostTitle'] ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="postContent" class="col-sm-2 control-label">post</label>
                  <div class="col-sm-10">

                    <textarea class="fr-view" id="editor" name="postContent"><?php echo $row['PostContent'] ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Post thumbnail</label>
                  <div class="col-sm-10">
                    <input type="file" name="postThum" class="form-control">
                    <input type="hidden" name="oldPostThum" value="<?php echo $row['PostImg']; ?>">
                  </div>
                </div>

                <div class="form-group">
                  <label for="postTitle" class="col-sm-2 control-label">Author</label>
                  <div class="col-sm-2">
                    <select class="form-control" name="postAuthor">
                      <?php
                      foreach ($users as $user) {
                        echo '<option value="' . $user['UserID'] . '"';
                        if ($row['PostAuthor'] == $user['UserID']) {
                          echo 'selected';
                        }
                        echo '>' . $user['Username'] . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary update-post">Update</button>
                  </div>
                </div>
              </div>
              <!-- Sidebar-->
              <div class="col-md-3">
                <div class="panel panel-primary">
                  <div class="panel-heading"><i class="fa fa-list-ul"></i>Categories</div>
                  <div class="panel-body cat-select">
                    <?php
                    $checkbox = explode(",", $row['PostCat']);
                    foreach ($cats as $cat) {
                      if (in_array($cat['CatName'], $checkbox)) {
                        $checked = $cat['CatName'];
                      }
                      echo
                        '<div class="checkbox">
                      <label>
                        <input type="checkbox" value="' . $cat['CatName'] . '" name="category[]"';
                      if (isset($checked) && $checked == $cat['CatName']) {
                        echo 'checked';
                      }
                      echo '>' . $cat['CatName'] . '
                      </label>
                    </div>';
                    }
                    ?>
                  </div>
                  <div class="panel-footer">
                    <a class="btn btn-default" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Add new category</a>
                    <div class="collapse" id="collapseExample">
                      <div class="form-group">
                        <label for="add-cat">Name</label>
                        <form action="categories.php?do=insert" method="post">
                          <input type="text" name="catName" class="form-control" id="add-cat" />
                          <input type="hidden" name="catDesc" />
                          <button type="submit" class="btn btn-default">Add</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!--tags area-->
                <div class="tags-area">
                  <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-tags"></i>Tags</div>
                    <div class="panel-body">
                      <input type="text" class="form-control tag-input" />
                      <small><i>Press commas to add tag</i></small>
                      <input type="hidden" class="tags-values" name="PostTag" value="<?php echo $row['PostTag']; ?>" />
                      <div class="added-tags">
                        <?php

                        $tags = explode(",", $row['PostTag']);
                        //print_r($tags);

                        foreach ($tags as $tag) {
                          if (!empty($tag)) {
                            echo '<span class="tags">' . $tag . '<i class="fa fa-times-circle" aria-hidden="true"></i></span>';
                          }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </section>
    <?php
      } else {
        $errorMsg = '<div class="alert alert-warning">This post is not exist</div>';
        redirectBack($errorMsg);
      }

      /*--------Update page--------*/
    } elseif ($do == 'update') {

      echo '<h3>Update post</h3>';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $postid     = $_POST['postid'];
        $title      = $_POST['postTitle'];
        $content    = $_POST['postContent'];
        $author     = $_POST['postAuthor'];
        $tag        = $_POST['PostTag'];
        $categories = isset($_POST['category']) ? $_POST['category'] : 'Uncategorised';
        $newcat     = implode(",", (array)$categories);

        //$picture    = image_uploader('image','uploads/');
        $picture    = !empty($_FILES['postThum']['name']) ? image_uploader('postThum', 'uploads/') : $_POST['oldPostThum'];

        /*Chek if the name is empty*/
        if (!empty($title)) {

          /*Update the database with the new info*/
          $stmt = $conn->prepare("UPDATE posts SET PostTitle = ? , PostContent = ?, PostAuthor = ?, PostTag = ?,  PostCat = ? , PostImg = ? WHERE PostID = ?");
          $stmt->execute(array($title, $content, $author, $tag, $newcat, $picture, $postid));

          $theMsg = '<div class="alert alert-success">the information was updated with success</div>';
          redirectBack($theMsg, 'back');
        } else {
          $errorMsg = '<div class="alert alert-danger">You must set the title of this post</div>';
          redirectBack($errorMsg, 'back');
        }
      }
      /*--------Delete page--------*/
    } elseif ($do == 'delete') {

      $postid = isset($_GET['postid']) || is_numeric($_GET['postid']) ? intval($_GET['postid']) : 0;

      $stmt = $conn->prepare("DELETE FROM posts WHERE PostID = ?");
      $stmt->execute(array($postid));

      $theMsg = '<div class="alert alert-success">Post was deleted successfuly</div>';
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