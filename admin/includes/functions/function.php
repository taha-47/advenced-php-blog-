<?php
/*
=================================
* Function
=================================
*/

/* Get the page title*/

function getPageTitle()
{
  global $pageTitle;
  if (isset($pageTitle)) {
    echo $pageTitle;
  } else {
    echo 'defualte';
  }
}

/* Redirect to the previous page*/

function redirectBack($theMsg, $url = null, $seconds = 3)
{
  if ($url == null) {
    $url = 'index.php';
    $link = 'Dashboard';
  } else {
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
      $url = $_SERVER['HTTP_REFERER'];
      $link = 'Previous Page';
    } else {
      $url  = 'index.php';
      $link = 'Dashboard';
    }
  }
  echo $theMsg . '<div class="alert alert-warning">You well be redirect to ' . $link . ' in ' . $seconds . ' seconds</div>';

  header("refresh: $seconds ; url=$url");
  exit();
}

/* Check if item is existe  in the database*/

function checkItem($select, $from, $value)
{

  global $conn;
  $stmt1 = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
  $stmt1->execute(array($value));
  $count = $stmt1->rowCount();

  return $count;
}

/* Get latest item from database */

function getLatest($select, $table, $order, $limit, $condation = '')
{

  global $conn;

  $stmtLatest = $conn->prepare("SELECT $select FROM $table $condation ORDER BY $order DESC LIMIT $limit");
  $stmtLatest->execute();
  $rows = $stmtLatest->fetchAll();
  return $rows;
}

/* Count the number of item in the database*/

function countItem($item, $table)
{
  global $conn;

  $stmtCount = $conn->prepare("SELECT COUNT($item) FROM $table");
  $stmtCount->execute();
  return $stmtCount->fetchColumn();
}

/* Image uploader function*/

function image_uploader($inputName, $dir)
{
  $imageName     = $_FILES[$inputName]['name'];
  $imageType     = $_FILES[$inputName]['type'];
  $imageTmpName  = $_FILES[$inputName]['tmp_name'];
  $imageError    = $_FILES[$inputName]['error'];
  $imageSize     = $_FILES[$inputName]['size'];
  $validExt      = ["png", "jpeg", "jpg", "gif"];
  $imageExt      = explode('.', $imageName);
  $imageExt      = strtolower(end($imageExt));
  $upload_dir    = $dir;

  if (!empty($imageName)) {
    if (in_array($imageExt, $validExt)) {
      if ($imageSize < 5000000) {
        if ($imageError == 0) {
          $image = 'image_' . uniqid('', true) . '.' . $imageExt;
          move_uploaded_file($imageTmpName, $upload_dir . $image);
        } else {
          echo '<div class="alert alert-warning">There\'s some error please try again</div>';
        }
      } else {
        echo '<div class="alert alert-warning">image size must be less than</div>';
      }
    } else {
      echo '<div class="alert alert-warning">Extentions not allowed</div>';
    }
  } else {
    $image = '';
  }
  return $image;
}

/*Get the Breaking news */

function getBreakingNews()
{

  global $conn;

  $stmt = $conn->prepare("SELECT * FROM posts ORDER BY PostID desc LIMIT 4");
  $stmt->execute();
  $postsTitle = $stmt->fetchAll();

  foreach ($postsTitle as $post) {
    echo '<li class="breaking-news"><a href="post.php?id= ' . $post['PostID'] . '">' . $post['PostTitle'] . '</a></li>';
  }
}

/* Get the Paginition */

function get_pagination()
{

  global $conn;

  $result_per_page = 1;

  /* Get nubmer of result form database*/
  $stmt = $conn->prepare("SELECT PostID FROM posts");
  $stmt->execute();
  $num_of_result = $stmt->rowCount();

  $num_of_page = ceil($num_of_result / $result_per_page);

  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  $page_first_result = ($page - 1) * $result_per_page;

  $stmt = $conn->prepare("SELECT * FROM posts LIMIT " . $page_first_result . ", " . $result_per_page . " ");
  $stmt->execute();
  $items = $stmt->fetchAll();

  echo
    '<nav aria-label="Page navigation">
      <ul class="pagination">';
  if ($page == 1) {
    $disabled = 'disabled';
    $page = 1;
  } else {
    $disabled = '';
    $page = $page - 1;
  }
  echo '<li class="' . $disabled . '"><a href="index.php?page=' . $page . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';

  for ($page = 1; $page <= $num_of_page; $page++) {

    if (isset($_GET['page']) && $page == $_GET['page']) {
      $active = 'active';
    } else {
      $active = '';
    }
    echo '<li class="' . $active . '"><a href="index.php?page=' . $page . '">' . $page . ' <span class="sr-only"></span></a></li>';
  }

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  // echo $page;
  if (isset($_GET['page']) && $page == $num_of_page) {
    $disabled = 'disabled';
    $page = $num_of_page;
  } else {
    $disabled = '';
    $page++;
  }

  echo
    '<li class="' . $disabled . '">
          <a href="index.php?page=' . $page . '" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
    </ul>
  </nav>';
}

/* Get comments */

function get_comments()
{

  global $conn;
  global $postid;
  /*Get the comments from database */
  $stmtCom = $conn->prepare("SELECT comments.*, users.Username, users.UserImg FROM comments INNER JOIN users ON users.UserID = C_author WHERE C_post = ? ORDER BY C_ID DESC");
  $stmtCom->execute(array($postid));
  $comts = $stmtCom->fetchAll();
  $comtsCount = $stmtCom->rowCount();

  /*Get the options from datebase */
  $stmtOptions = $conn->prepare("SELECT OptionsValue FROM options WHERE OptionsID IN (5,6,9)");
  $stmtOptions->execute();
  $options = $stmtOptions->fetchAll();

  if ($comtsCount > 0) {
    echo '
  <div class="comment-list">
    <ul class="list-unstyled">';
    foreach ($comts as $comt) {
      if ($comt['C_status'] == 1) {
        $date_format = $options['0']['OptionsValue'] . ' ' . $options['1']['OptionsValue'];
        echo '
          <li>
            <div class="comment-body">
              <div class="comment-author">';
        if (!empty($comt['UserImg'])) {
          echo '<img src="admin/uploads/user-profile/' . $comt['UserImg'] . '" class="img-circle img-thumbnail" />';
        } else {
          echo '<img src="admin/asset/images/userpic.png" class="img-circle img-thumbnail"/>';
        }
        echo '
                <div class="author-meta">
                  <div class="author-name">' . $comt['Username'] . '</div>
                  <div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i> ' . date($date_format, strtotime($comt['C_date'])) . '</div>
                </div>
              </div>

              <div class="comment-content">
                <p>' . $comt['Comment'] . '<p>
              </div>
            </div>
          </li>';
      }
    }
    echo '</ul>
  </div>';
  } else {
    echo '<div class="alert alert-warning">There is no comments</div>';
  }
}

/*Get the comment form */

function get_comment_form()
{
  global $conn;
  global $postid;
  echo '
    <div class="form-area">
      <form action="post.php?id=' . $postid . '" method="post" class="form-horizontal">
      <h4>Leave a Reply</h4>
        <div class="form-group">
          <div class="col-sm-12">
              <textarea name="comment" class="form-control" placeholder="Your comment" rows="5"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary">Submit comment</button>
          </div>
        </div>
      </form>
    </div>';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $author = $_SESSION['ID'];
    $comment = $_POST['comment'];

    /*insert values into database*/
    if (!empty($comment)) {
      $stmt = $conn->prepare("INSERT INTO comments (Comment, C_author, C_post, C_date) VALUES (:comment , :c_name, :c_post, now())");
      $stmt->execute(array(
        'comment'  => $comment,
        'c_name'   => $author,
        'c_post'   => $postid
      ));
    } else {
      echo '<div alert alert-danger>Comment must not be empty</div>';
    }
  }
}
