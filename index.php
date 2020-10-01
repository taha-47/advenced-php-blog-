<?php

$pageTitle = 'Posts';

include 'init.php';

/* Get the posts */

$stmtPost = $conn->prepare("SELECT posts.*, users.Username FROM posts INNER JOIN users ON users.UserID = PostAuthor ORDER BY PostID DESC");
$stmtPost->execute();
$posts = $stmtPost->fetchAll();

/*Get the options from datebase */
$stmtOptions = $conn->prepare("SELECT OptionsValue FROM options WHERE OptionsID IN (5,6,9)");
$stmtOptions->execute();
$options = $stmtOptions->fetchAll();

?>

<div class="container">
  <div class="row">
    <div class="col-md-8">
      <?php
      if ($options['2']['OptionsValue'] == 1) { ?>
        <div class="main-breaking">
          <div class="name">Breaking news</div>
          <div class="breaking-body">
            <ul class="Breaking-scroll">
              <?php
              getBreakingNews();
              ?>
            </ul>
          </div>

        </div>
      <?php
      }
      $date_format = $options['0']['OptionsValue'] . ' ' . $options['1']['OptionsValue']; // get the date format
      foreach ($posts as $post) {

        $exerpet = strip_tags($post['PostContent']);
        $exerpet = substr($exerpet, 0, 160);
        echo '
          <div class="post">
            <div class="post-thumb">
              <a href="post.php?id=' . $post['PostID'] . '" >';
        if (!empty($post['PostImg'])) {
          echo '<img src="admin/uploads/' . $post['PostImg'] . '" alt="' . $post['PostTitle'] . '"/>';
        } else {
          echo '<img src="admin/asset/images/no-img.png" alt="' . $post['PostTitle'] . '"/>';
        }
        echo '</a>
            </div><div class="clear"></div>
              
            <div class="post-info">
              <h4><a href="post.php?id=' . $post['PostID'] . '">' . $post['PostTitle'] . '</a></h4>
              <span class="author"><i class="fa fa-user"></i>' . $post['Username'] . '</span>
              <span class="date"><i class="fa fa-calendar"></i>' . date($date_format, strtotime($post['PostDate'])) . '</span>
              <p>' . $exerpet . ' [...]</p>
              <a href="post.php?id=' . $post['PostID'] . '" class="btn btn-primary btn-sm">Read more</a>
            </div>
                
          </div>';
      } ?>
      <div class="pagination-area text-center">
        <?php
        get_pagination();
        ?>
      </div>
    </div>
    <div class="col-md-4">
      <?php include $tmplt . 'sidebar.php'; ?>
    </div>
  </div>
</div>

<?php
include $tmplt . 'footer.php'; ?>