<?php
/*
===========================
Post page 
===========================
*/
session_start();

$pageTitle = 'Article';

include 'init.php';

$postid = isset($_GET['id']) || is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

/*select the currant id from database*/
$stmt = $conn->prepare("SELECT posts.*, users.UserID, users.Username , users.Fullname, users.Biographical, users.UserRole, users.UserImg FROM posts INNER JOIN users ON users.UserID = PostAuthor WHERE PostID = ? ORDER BY PostID DESC LIMIT 1");
$stmt->execute(array($postid));
$post = $stmt->fetch();
$count = $stmt->rowCount();


/*Get related postes*/

$selected_cat = $post["PostCat"];
$stmt = $conn->prepare("SELECT * FROM Posts WHERE PostCat LIKE :category ORDER BY RAND() LIMIT 3");
$stmt->bindValue(':category', '%' . $selected_cat . '%');
$stmt->execute();
$r_posts = $stmt->fetchAll();
$count = $stmt->rowCount();

/*Get the options from datebase */
$stmtOptions = $conn->prepare("SELECT OptionsValue FROM options WHERE OptionsID IN (5,6,7)");
$stmtOptions->execute();
$options = $stmtOptions->fetchAll();

?>

<div class="">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <?php if (isset($postid) && $postid > 0) { ?>
          <article class="post-content">
            <div class="title">
              <h3><?php echo $post['PostTitle']; ?></h3>
            </div>
            <div class="content">
              <?php
              $date_format = $options['0']['OptionsValue'] . ' ' . $options['1']['OptionsValue'];
              echo '
              <div class="post-info">
                <span class="author"><i class="fa fa-user"></i>' . $post['Username'] . '</span>
                <span class="date"><i class="fa fa-calendar"></i>' . date($date_format, strtotime($post['PostDate'])) . '</span>
              </div>';
              echo  $post['PostContent']; ?>
            </div>
          </article>

          <!-- Auther area-->
          <section class="auther-area">
            <div class="auther-info">
              <?php
              if ($count > 0) {
                if (!empty($post['UserImg'])) {
                  echo '<img src="admin/uploads/user-profile/' . $post['UserImg'] . '"/>';
                } else {
                  echo '<img src="admin/asset/images/userpic.png" />';
                }
                echo
                  '<div class="info">
                <a href="profile.php?id=' . $post['UserID'] . '">
                  <h3 class="author-name">' . $post['Fullname'] . '</h3>
                </a>
                <span class="author-role">' . $post['UserRole'] . '</span>
                <p class="author-bio">' . $post['Biographical'] . '</p>
              </div>';
              }
              ?>
            </div>
          </section>

          <!-- Comments Area-->
          <section class="comments">
            <h3>Comments</h3>
            <?php
            if ($options['2']['OptionsValue'] == 1) {
              if (isset($_SESSION['username'])) {
                echo get_comments();
                echo get_comment_form();
              } else {
                echo '<div class="alert alert-warning">You must <a href="admin/index.php">login</a> to be able to comment</div>';
              }
            } else {
              echo '<div class="alert alert-warning">Comments are disabled</div>';
            }
            ?>
          </section>

          <!-- Related Post-->
          <section class="related-post">
            <h3>Related posts</h3>
            <div class="row">
              <?php
              $exerpet = strip_tags($post['PostContent']);
              $exerpet = substr($exerpet, 0, 100);
              if ($count > 0) {
                foreach ($r_posts as $post) {
                  echo '
                <div class="col-md-4">
                  <div class="post">';
                  if (!empty($post['PostImg'])) {
                    echo '<img src="admin/uploads/' . $post['PostImg'] . '" class="img-responsive"/>';
                  } else {
                    echo '<img src="admin/asset/images/no-img.png" class="img-responsive"/>';
                  }
                  echo
                    '<a href="profile.php?id=' . $post['PostID'] . '">
                      <h5 class="post-title">' . $post['PostTitle'] . '</h5>
                  </a>
                  <p class="post-excerpt">' . $exerpet . '</p>';
                  echo '
                    </div>
                </div>';
                }
              }
              ?>
            </div>
          </section>
      </div>
    <?php
        } else {
          echo '<div class="alert alert-danger">no such article</div></div>';
        } ?>
    <!--Sidebar area-->
    <div class="col-md-4">
      <?php include $tmplt . 'sidebar.php'; ?>
    </div>
    </div>
  </div>
</div>

<?php
include $tmplt . 'footer.php';
?>