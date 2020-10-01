<?php
/*
================================
* Admin Sideaera
===============================
*/
?>

<div class="side-area">
  <ul class="list-unstyled">

    <!-- Dashbord -->
    <li class="main-list <?php if ($pageTitle == 'Dashboard') {
                            echo 'active';
                          } else {
                            echo '';
                          } ?>">
      <a href="dashboard.php"><i class="fa fa-dashboard"></i>
        <div class="admin-menu-name">dashboard</div>
      </a>
    </li>

    <!-- Pages -->
    <li class="main-list <?php if ($pageTitle == 'Pages') {
                            echo 'active';
                          } else {
                            echo '';
                          } ?>">
      <a href="pages.php">
        <i class="fa fa-file"></i>
        <div class="admin-menu-name">Pages</div>
      </a>
      <ul class="list-unstyled <?php if ($pageTitle == 'Pages') {
                                  echo 'open';
                                } else {
                                  echo 'close';
                                } ?>" id="article">
        <li><a href="pages.php">All pages</a></li>
        <li><a href="pages.php?do=add">Add pages</a></li>
      </ul>
    </li>

    <!-- Posts -->
    <li class="main-list <?php if ($pageTitle == 'Posts') {
                            echo 'active';
                          } else {
                            echo '';
                          } ?>">
      <a href="posts.php">
        <i class="fa fa-file-text-o"></i>
        <div class="admin-menu-name">Posts</div>
      </a>
      <ul class="list-unstyled <?php if ($pageTitle == 'Posts') {
                                  echo 'open';
                                } else {
                                  echo 'close';
                                } ?>" id="article">
        <li><a href="posts.php">All posts</a></li>
        <li><a href="posts.php?do=add">Add post</a></li>
      </ul>
    </li>

    <!-- Categories -->
    <li class="main-list <?php if ($pageTitle == 'Categories') {
                            echo 'active';
                          } ?>">
      <a href="categories.php">
        <i class="fa fa-tags"></i>
        <div class="admin-menu-name">Categories</div>
      </a>
    </li>

    <!-- Comments -->
    <li class="main-list <?php if ($pageTitle == 'Comments') {
                            echo 'active';
                          } ?>">
      <a href="comments.php"><i class="fa fa-comments-o"></i>
        <div class="admin-menu-name">Comments</div>
      </a>
    </li>
    <!-- Users -->
    <li class="main-list <?php if ($pageTitle == 'User profile') {
                            echo 'active';
                          } ?>">
      <a href="users.php"><i class="fa fa-user"></i>
        <div class="admin-menu-name">Users</div>
      </a>
      <ul class="list-unstyled <?php if ($pageTitle == 'User profile') {
                                  echo 'open';
                                } else {
                                  echo 'close';
                                } ?> ">
        <li><a href="users.php">All users</a></li>
        <li><a href="users.php?do=add">Add user</a></li>
        <li><a href="users.php?do=edit-profile&userid=<?php echo $_SESSION['ID']; ?>">Your Profile</a></li>
      </ul>
    </li>

    <!-- Options -->
    <li class="main-list <?php if ($pageTitle == 'Options') {
                            echo 'active';
                          } ?>">
      <a href="options.php">
        <i class="fa fa-sliders" aria-hidden="true"></i>
        <div class="admin-menu-name">Options</div>
      </a>
      <ul class="list-unstyled <?php if ($pageTitle == 'Options') {
                                  echo 'open';
                                } else {
                                  echo 'close';
                                } ?> ">
        <li><a href="?do=general">General</a></li>
        <li><a href="?do=descussion">Descussion</a></li>
        <li><a href="?do=menu">Menu</a></li>
      </ul>
    </li>

    <!-- Visit site -->
    <li class="main-list">
      <a href="../index.php" target="_blank">
        <i class="fa fa-globe"></i>
        <div class="admin-menu-name">Visite site</div>
      </a>
    </li>

    <!-- Logout -->
    <li class="main-list">
      <a href="logout.php">
        <i class="fa fa-sign-out"></i>
        <div class="admin-menu-name">Logout</div>
      </a>
    </li>
  </ul>
</div>