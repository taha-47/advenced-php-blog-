<?php


/* Get the latest post in the week */
$stmtWeek = $conn->prepare("SELECT * FROM `posts` WHERE PostDate BETWEEN date_sub(now(),INTERVAL 1 WEEK) and now() ORDER BY PostDate DESC LIMIT 2");
$stmtWeek->execute();
$postsWeek = $stmtWeek->fetchAll();

/* Get the latest post in the month */
$stmtMonth = $conn->prepare("SELECT * FROM `posts` WHERE PostDate BETWEEN date_sub(now(),INTERVAL 1 month) and now() ORDER BY PostDate DESC LIMIT 2");
$stmtMonth->execute();
$postsMonth = $stmtMonth->fetchAll();

/* Get the categories */
$stmtCat = $conn->prepare("SELECT * FROM categories ORDER BY CatID DESC LIMIT 5");
$stmtCat->execute();
$cats = $stmtCat->fetchAll();
$count = $stmtCat->rowCount();

?>

<div class="sidebar">
  <section class="search">
    <form action="search.php" method="get">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." name="searchKey">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>
  </section>

  <section class="popular-post">
    <div class="widget-title">Popular Posts</div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#week" aria-controls="home" role="tab" data-toggle="tab">Week</a></li>
      <li role="presentation"><a href="#month" aria-controls="profile" role="tab" data-toggle="tab">Month</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="week">
        <ul class="list-unstyled latest-posts">
          <?php
          foreach ($postsWeek as $post) {
            if (!empty($post['PostImg'])) {
              echo
                '<li>
                  <a href="post.php?id=' . $post['PostID'] . '" class="thumb">
                    <img src="admin/uploads/' . $post['PostImg'] . '" >
                  </a>
                  <a href="post.php?id=' . $post['PostID'] . '" class="post-title">' . $post['PostTitle'] . '</a>
                </li>';
            } else {
              echo
                '<li>
                <a href="post.php?id=' . $post['PostID'] . '" class="thumb">
                <img src="admin/asset/images/no-img.png"/>
                </a>
                <a href="post.php?id=' . $post['PostID'] . '" class="post-title">' . $post['PostTitle'] . '</a>
              </li>';
            }
          }
          ?>
        </ul>
      </div>

      <div role="tabpanel" class="tab-pane" id="month">
        <ul class="list-unstyled latest-posts">
          <?php
          foreach ($postsMonth as $post) {
            echo
              '<li>
                  <a href="post.php?id=' . $post['PostID'] . '" class="thumb">
                    <img src="admin/uploads/' . $post['PostImg'] . '" >
                  </a>
                  <a href="post.php?id=' . $post['PostID'] . '" class="post-title">' . $post['PostTitle'] . '</a>
                </li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </section>

  <section class="categories">
    <div class="widget-title">Categories</div>
    <div>
      <?php
      if ($count > 0) {
        foreach ($cats as $cat) {
          echo
            '<ul class="list-unstyled cats">
            <li>
              <a href="search.php?searchKey=' . $cat['CatName'] . '">
                <i class="fa fa-tags"></i><span class="cat-name">' . $cat['CatName'] . '</span>
              </a>
            </li>
          </ul>';
        }
      } else {
        echo '<div class="alert alert-default">there is no category</div>';
      }
      ?>
    </div>
  </section>
  <section class="mail-list">
    <div class="mail-content text-center">
      <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
      <p>Stil recive our update just subscribe to the newslatter</p>
      <input type="email" class="form-control" placeholder="Type your email">
      <input type="submit" value="Send" class="btn btn-block btn-info" />
    </div>
  </section>
</div>