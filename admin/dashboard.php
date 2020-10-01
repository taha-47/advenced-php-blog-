<?php
/*
=====================================
 Dashboard Page
=====================================
*/

session_start();
session_regenerate_id();
$pageTitle = 'Dashboard';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get the latest article published */
  $latestArticles = getLatest("*", "posts", "PostID", 5);

  /*Get the latest comments */
  $condation = "WHERE C_status != 0";
  $latestComments = getLatest("*", "comments", "C_ID", 5, $condation);

?>

  <section class="dash-status">
    <div class="content">
      <div class="row">
        <div class="stat-card text-center">
          <div class="col-md-3">
            <div class="pages-num stat-item">
              <i class="fa fa-file" aria-hidden="true"></i>
              <div class="info">
                Total pages
                <div class="stat-val"><a href="pages.php" target="_blanck"><?php echo countItem("PageID", "pages"); ?></a></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="blog-num stat-item">
              <i class="fa fa-file-text-o" aria-hidden="true"></i>
              <div class="info">
                Total blog
                <div class="stat-val"><a href="posts.php" target="_blanck"><?php echo countItem("PostID", "posts"); ?></a></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="comments-num stat-item">
              <i class="fa fa-comments-o" aria-hidden="true"></i>
              <div class="info">
                Total comments
                <div class="stat-val"><a href="comments.php" target="_blanck"><?php echo countItem("C_ID", "comments"); ?></a></div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="users-num stat-item">
              <i class="fa fa-users" aria-hidden="true"></i>
              <div class="info">
                Total users
                <div class="stat-val"><a href="users.php" target="_blanck"><?php echo countItem("UserID", "users"); ?></a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="latest">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">Latest <strong><?php echo count($latestArticles); ?></strong> article</div>
          <div class="panel-body">
            <ul class="list-unstyled latest-item">
              <?php
              foreach ($latestArticles as $article) {
                echo '<li><a href="posts.php?do=edit&postid=' . $article['PostID'] . '">' . $article['PostTitle'] . '</a> </li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">latast <strong><?php echo count($latestComments); ?></strong> comments</div>
          <div class="panel-body">
            <ul class="list-unstyled latest-item">
              <?php
              foreach ($latestComments as $comment) {
                echo '<li><a href="comments.php?do=edit&comid=' . $comment['C_ID'] . '">' . $comment['Comment'] . '</a> </li>';
              }
              ?>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </section>

<?php
  include $tmplt . "footer.php";
} else {
  header('Location: index.php');
  exit();
}

?>