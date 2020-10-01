<?php

/*
===========================
Page page 
===========================
*/
session_start();

$pageTitle = 'Page';

include 'init.php';

$pageid = isset($_GET['id']) || is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

/*select the currant id from database*/
$stmt = $conn->prepare("SELECT pages.*, users.Username FROM pages INNER JOIN users ON users.UserID = PageAuthor WHERE PageID = ? ORDER BY PageID DESC LIMIT 1");
$stmt->execute(array($pageid));
$page = $stmt->fetch();
$count = $stmt->rowCount();
?>
<div class="">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <?php if (isset($pageid) && $pageid > 0) { ?>
          <article class="post-content">
            <div class="title">
              <h3><?php echo $page['PageTitle']; ?></h3>
            </div>
            <div class="content">
              <?php
              echo  $page['PageContent']; ?>
            </div>
          </article>
      </div>
    <?php } else {
          echo '<div class="alert alert-danger">This page not found</div></div>';
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