<?php

$pageTitle = 'Search';

include 'init.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $keyword = filter_var($_GET['searchKey'], FILTER_SANITIZE_STRING);

  /*Search keyword in the post title*/
  $stmtPost = $conn->prepare("SELECT posts.*, users.Username FROM posts INNER JOIN users ON users.UserID = PostAuthor WHERE PostTitle LIKE :keyword OR PostCat LIKE :keyword ORDER BY PostID DESC");
  $stmtPost->bindValue(':keyword', '%' . $keyword . '%');
  $stmtPost->execute();
  $posts = $stmtPost->fetchAll();
  $count = $stmtPost->rowCount();
}
if ($keyword == '' || $count < 1) {
  echo
    '<div class="container">
        <div class="row">
          <p>Search for : <strong>" ' . $keyword . ' "</strong></p>
            <div class="col-md-8"> 
              <h3 class="alert alert-danger">No resault :(</h3>  
            </div>
            <div class="col-md-4">';
  include $tmplt . 'sidebar.php';
  echo
    '</div>
        </div>
      </div>';
} else { ?>
  <div class="container">
    <div class="row">
      <p>Search for : <strong><?php echo "'$keyword'"; ?></strong></p>
      <div class="col-md-8">
        <?php
        foreach ($posts as $post) {
          $exerpet = strip_tags($post['PostContent']);
          $exerpet = substr($exerpet, 0, 160);
          echo
            '<div class="post">
                    <div class="post-thumb">
                      <a href="post.php?id=' . $post['PostID'] . '" >';
          if (!empty($post['PostImg'])) {
            echo '<img src="admin/uploads/' . $post['PostImg'] . '" alt="' . $post['PostTitle'] . '"/>';
          } else {
            echo '<img src="admin/asset/images/no-img.png" alt="' . $post['PostTitle'] . '"/>';
          }
          echo
            '</a>
                </div>
                  <div class="clear"></div>
                    <div class="post-info">
                      <h3><a href="post.php?id=' . $post['PostID'] . '">' . $post['PostTitle'] . '</a></h3>
                      <span class="author"><i class="fa fa-user"></i>' . $post['Username'] . '</span>
                      <span class="date"><i class="fa fa-calendar"></i>' . $post['PostDate'] . '</span>
                      <p>' . $exerpet . ' [...]</p>
                      <a href="post.php?id=' . $post['PostID'] . '" class="btn btn-primary btn-sm">Read more</a>
                    </div>
                  </div>';
        }
        ?>
      </div>
      <div class="col-md-4">
        <?php include $tmplt . 'sidebar.php'; ?>
      </div>
    </div>
  </div>
<?php }

include $tmplt . 'footer.php'; ?>