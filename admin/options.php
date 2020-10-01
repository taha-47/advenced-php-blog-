<?php
/*
==============================
* Options page
==============================
*/
ob_start();
session_start();
$pageTitle = 'Options';
if (isset($_SESSION['username'])) {
  include 'init.php';

  /*Get all categories*/
  $stmtCat = $conn->prepare("SELECT * FROM categories");
  $stmtCat->execute();
  $cats = $stmtCat->fetchAll();

  /*Get all pages*/
  $stmtPages = $conn->prepare("SELECT * FROM pages");
  $stmtPages->execute();
  $pages = $stmtPages->fetchAll();

  /*Get all options*/
  $stmtOptions = $conn->prepare("SELECT * FROM options");
  $stmtOptions->execute();
  $options = $stmtOptions->fetchAll();

  $do = isset($_GET['do']) ? $_GET['do'] : 'general';
  $action = isset($_GET['action']) ? $_GET['action'] : '';
?>

  <div class="content options-page">
    <?php

    /*--------General page--------*/
    if ($do == 'general') { ?>

      <h2>Options</h2>
      <form class="form-horizontal" action="?do=update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="option_page" value="general">
        <div class="form-group">
          <label for="blogname" class="col-sm-2 control-label">Site title</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" id="blogname" name="blogname" value="<?php echo $options['0']['OptionsValue']; ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="blogdesc" class="col-sm-2 control-label">Site description</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" id="blogdesc" name="blogdesc" value="<?php echo $options['1']['OptionsValue']; ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="adminemail" class="col-sm-2 control-label">Administration Email</label>
          <div class="col-sm-4">
            <input type="email" class="form-control" id="adminemail" name="adminemail" value="<?php echo $options['2']['OptionsValue']; ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">Site Logo</label>
          <div class="col-sm-4">
            <input type="file" name="sitelogo" class="form-control">
            <input type="hidden" name="oldLogo" value="<?php echo $options['9']['OptionsValue']; ?>">
            <small><i>choose site logo dimitions 270 x 60</i></small>
          </div>
        </div>

        <div class="form-group">
          <label for="favicon" class="col-sm-2 control-label">Favicon</label>
          <div class="col-sm-4">
            <input type="file" name="favicon" class="form-control">
            <input type="hidden" name="oldFavicon" value="<?php echo $options['10']['OptionsValue']; ?>">
            <small><i>choose favicon dimitions 30 x 30</i></small>
          </div>
        </div>

        <div class="form-group">
          <label for="blogdesc" class="col-sm-2 control-label">Timezone</label>
          <div class="col-sm-4">
            <?php
            $OptionsArray = timezone_identifiers_list();
            echo '<select class="form-control" name="timezone">';
            foreach ($OptionsArray as $value) {
              echo '<option value="' . $value . '"';
              if ($options['3']['OptionsValue'] == $value) {
                echo 'selected';
              }
              echo '>' . $value . '</option>';
            }
            echo '</select>';
            ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Date format</label>
          <div class="col-sm-6">
            <div>
              <label>
                <input type="radio" name="dateformat" value="F j, Y" <?php if ($options['4']['OptionsValue'] == "F j, Y") {
                                                                        echo 'checked';
                                                                      } ?> />
                <span><?php echo date("F j, Y"); ?></span>
              </label>
            </div>

            <div>
              <label>
                <input type="radio" name="dateformat" value="Y-m-d" <?php if ($options['4']['OptionsValue'] == "Y-m-d") {
                                                                      echo 'checked';
                                                                    } ?> />
                <span><?php echo date("Y-m-d"); ?></span>
              </label>
            </div>

            <div>
              <label>
                <input type="radio" name="dateformat" value="m/d/Y" <?php if ($options['4']['OptionsValue'] == "m/d/Y") {
                                                                      echo 'checked';
                                                                    } ?> />
                <span><?php echo date("m/d/Y"); ?></span>
              </label>
            </div>

            <div>
              <label>
                <input type="radio" name="dateformat" value="d/m/Y" <?php if ($options['4']['OptionsValue'] == "d/m/Y") {
                                                                      echo 'checked';
                                                                    } ?> />
                <span><?php echo date("d/m/Y"); ?></span>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label">Time format</label>
          <div class="col-sm-6">
            <div>
              <label>
                <input type="radio" name="timeformat" value="g:i a" <?php if ($options['5']['OptionsValue'] == "g:i a") {
                                                                      echo 'checked';
                                                                    } ?> />
                <span><?php echo date("g:i a"); ?></span>
              </label>
            </div>

            <div>
              <label>
                <input type="radio" name="timeformat" value="g:i A" <?php if ($options['5']['OptionsValue'] == "g:i A") {
                                                                      echo 'checked';
                                                                    } ?> />
                <span><?php echo date("g:i A"); ?></span>
              </label>
            </div>

            <div>
              <label>
                <input type="radio" name="timeformat" value="H:i" <?php if ($options['5']['OptionsValue'] == "H:i") {
                                                                    echo 'checked';
                                                                  } ?> />
                <span><?php echo date("H:i"); ?></span>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
  </div>
  </form>
<?php
      /*-- Discussion Settings --*/
    } elseif ($do == 'descussion') { ?>

  <h2>Discussion Settings</h2>
  <form class="form-horizontal" action="?do=update" method="post">
    <input type="hidden" name="option_page" value="descussion">
    <div class="form-group">
      <label for="allowcomments" class="col-sm-3 control-label">Allow comments</label>
      <div class="col-sm-4">
        <input type="checkbox" id="allowcomments" name="allowcomments" value="1" <?php if ($options['6']['OptionsValue'] == 1) {
                                                                                    echo 'checked';
                                                                                  } ?>>
      </div>
    </div>

    <div class="form-group">
      <label for="allowdate" class="col-sm-3 control-label">Display date in top menu</label>
      <div class="col-sm-4">
        <input type="checkbox" id="allowdate" name="allowdate" value="1" <?php if ($options['7']['OptionsValue'] == 1) {
                                                                            echo 'checked';
                                                                          } ?>>
      </div>
    </div>

    <div class="form-group">
      <label for="breaking-news" class="col-sm-3 control-label">Display breaking news</label>
      <div class="col-sm-4">
        <input type="checkbox" id="breaking-news" name="displayBreakingNews" value="1" <?php if ($options['8']['OptionsValue'] == 1) {
                                                                                          echo 'checked';
                                                                                        } ?>>
      </div>
    </div>

    <div class="form-group">
      <div class=" col-sm-10">
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    </div>
  </form>

<?php
      /*--------Menu page--------*/
    } elseif ($do == 'menu') {
      $stmt = $conn->prepare("SELECT * FROM menus");
      $stmt->execute();
      $menusName = $stmt->fetchAll();
?>
  <h2>Menu</h2>
  <div class="row">
    <div class="manage-menus">
      <form action="?do=menu&action=" method="get" class="form-inline">
        <label>Select menu</label>
        <select class="form-control" name="">
          <?php
          foreach ($menusName as $menu) {
            echo '<option value="' . $menu['MenuID'] . '">' .  $menu['MenuName'] . '</option>';
          }
          ?>
        </select>
        <input type="submit" class="btn btn-primary btn-sm" value="Select" />
        <span>Or
          <a href="?do=menu&action=edit&menuid=0">Create new menu</a>
        </span>
      </form>
    </div>
    <?php

      $menuid = isset($_GET['menuid']) || is_numeric($_GET['menuid']) ? intval($_GET['menuid']) : 0;

      /*Get all menus*/
      $stmtMenus = $conn->prepare("SELECT * FROM menus WHERE MenuID =?");
      $stmtMenus->execute(array($menuid));
      $menu = $stmtMenus->fetch();
      $countMenus = $stmtMenus->rowCount(); ?>
    <?php
      if ($countMenus > 0) {
    ?>
      <div class="col-md-6">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Pages</a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <?php
                echo
                  '<div class="page-box">';
                foreach ($pages as $page) {
                  echo
                    '<div class="checkbox">
                    <label>
                      <input class="menu-select" type="checkbox" value="' . $page['PageTitle'] . '" name="page[]" >' . $page['PageTitle'] . '
                    </label>
                  </div>';
                }
                echo
                  '</div>
                  <label><input type="checkbox" class="select-all"/> Select All</label>
                  <input type="button" class="btn btn-info btn-xs btn-add-menu" value="Add to menu" />';
                ?>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTow">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Categories</a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">

                <?php
                echo
                  '<div class="categories-box">';
                foreach ($cats as $cat) {
                  echo
                    '<div class="checkbox">
                    <label>
                      <input class="menu-select" type="checkbox" value="' . $cat['CatName'] . '" name="category[]" >' . $cat['CatName'] . '
                    </label>
                  </div>';
                }
                echo
                  '</div>
                <label><input type="checkbox" class="select-all"/> Select All</label>
                <input type="button" class="btn btn-info btn-xs btn-add-menu" value="Add to menu" />';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- menu settings culomn-->
      <div class="menu-area">
        <div class="col-sm-6">
          <div class="menu">
            <div class="form">
              <form action="?do=insert-menu" method="post" class="form-inline">
                <div class="form-group">
                  <label>Menu name</label>
                  <input type="hidden" name="menuID" value="<?php echo $menu['MenuID']; ?>" />
                  <input type="text" name="currentMenu" class="form-control input-sm" placeholder="Menu name" required="requred" value="<?php echo $menu['MenuName']; ?>">
                  <input type="hidden" name="menuRequest" value="saveMenu">
                </div>
                <input type="submit" value="Save Menu" class="btn btn-primary btn-sm save-menu">
            </div>
            <div class="menu-list">
              <input type="hidden" id="menuContent" name="menuContent" />
              <?php
              if (!empty($menu['MenuItem'])) {
                $menu = explode(',', $menu['MenuItem']);
                foreach ($menu as $item) {
                  echo '<div class="menu-item">' . $item . '<span class="close"><i class="fa fa-times-circle" aria-hidden="true"></i></span></div>';
                }
              }
              ?>
            </div>
            </form>
          </div>
        </div>
      </div>
    <?php
      } else { ?>
      <div class="col-md-6">
        <div class="disabled"></div>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Pages</a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <?php
                echo
                  '<div class="page-box">';
                foreach ($pages as $page) {
                  echo
                    '<div class="checkbox">
                      <label>
                        <input class="menu-select" type="checkbox" value="' . $page['PageTitle'] . '" name="page[]" >' . $page['PageTitle'] . '
                      </label>
                    </div>';
                }
                echo
                  '</div>
                    <label><input type="checkbox" class="select-all"/> Select All</label>
                    <input type="button" class="btn btn-info btn-xs btn-add-menu" value="Add to menu" />';
                ?>
              </div>
            </div>
          </div>

          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTow">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Categories</a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
                <?php
                echo
                  '<div class="categories-box">';
                foreach ($cats as $cat) {
                  echo
                    '<div class="checkbox">
                      <label>
                        <input class="menu-select" type="checkbox" value="' . $cat['CatName'] . '" name="category[]" >' . $cat['CatName'] . '
                      </label>
                    </div>';
                }
                echo
                  '</div>
                  <label><input type="checkbox" class="select-all"/> Select All</label>
                  <input type="button" class="btn btn-info btn-xs btn-add-menu" value="Add to menu" />';
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- menu settings culomn-->

      <div class="create-menu">
        <div class="col-sm-6">
          <div class="new-menu">
            <div class="form">
              <form action="?do=insert-menu" method="post" class="form-inline">
                <div class="form-group">
                  <label>Menu name</label>
                  <input type="text" name="newMenu" class="form-control input-sm" placeholder="Menu name" required="requred">
                  <input type="hidden" name="menuRequest" value="createMenu">
                </div>
                <input type="submit" value="Create menu" class="btn btn-primary btn-sm">
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php
        //} 
      }
    ?>
  </div>

<?php
      /*--------Insert Menu--------*/
    } elseif ($do == 'insert-menu') {

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $menu_request = $_POST['menuRequest'];

        if ($menu_request == "createMenu") {

          $menu_name = $_POST['newMenu'];

          $check = checkItem("menuName", "menus", $menu_name);
          if ($check == 0) {
            $menu = $conn->prepare("INSERT INTO menus (MenuName) VALUES (:name)");
            $menu->execute(array(
              'name' => $menu_name
            ));

            /*Success message*/
            $theMsg = '<div class="alert alert-success">Menu ceateded with success</div>';
            redirectBack($theMsg, 'back');
          } else {
            /*error message*/
            $theMsg = '<div class="alert alert-warningess">Menu already exist</div>';
            redirectBack($theMsg, 'back');
          }
        } elseif ($menu_request == "saveMenu") {
          $menuID   = $_POST['menuID'];
          $menuName = $_POST['currentMenu'];
          $content  = $_POST['menuContent'];

          if (!empty($content) && !empty($menuName)) {
            $stmt = $conn->prepare("UPDATE menus SET MenuName = ? , MenuItem = ? WHERE MenuID = ?");
            $stmt->execute(array($menuName, $content, $menuID));

            /*Success message*/
            $theMsg = '<div class="alert alert-success">Menu saved successfully</div>';
            redirectBack($theMsg, 'back');
          } else {
            $theMsg = '<div class="alert alert-success">Menu saved successfully</div>';
            redirectBack($theMsg, 'back');
          }
        }
      }

      /*--------Insert general options--------*/
    } elseif ($do == 'update') {

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $current_page = $_POST["option_page"];

        if ($current_page == "general") {

          $siteName     = $_POST["blogname"];
          $siteDesc     = $_POST["blogdesc"];
          $adminEmail   = $_POST["adminemail"];
          $siteLogo     = !empty($_FILES['sitelogo']['name']) ? image_uploader('sitelogo', 'uploads/') : $_POST['oldLogo'];
          $favicon      = !empty($_FILES['favicon']['name']) ? image_uploader('favicon', 'uploads/') : $_POST['oldFavicon'];
          $timeZone     = $_POST["timezone"];
          $dateFormat   = $_POST["dateformat"];
          $timeFormat   = $_POST["timeformat"];

          $stmt = $conn->prepare("UPDATE options
          SET OptionsValue = 
            CASE 
            WHEN OptionsName = 'Site name' THEN ?
            WHEN OptionsName = 'Site description' THEN ?
            WHEN OptionsName = 'Admin Email' THEN ?
            WHEN OptionsName = 'Site logo' THEN ?
            WHEN OptionsName = 'Favicon' THEN ?
            WHEN OptionsName = 'Timezone' THEN ?
            WHEN OptionsName = 'Date Formate' THEN ?
            WHEN OptionsName = 'Time Formate' THEN ?
            END
            WHERE OptionsName IN ('Site name', 'Site description', 'Admin Email', 'Site logo', 'Favicon', 'Timezone', 'Date Formate', 'Time Formate')");
          $stmt->execute(array($siteName, $siteDesc, $adminEmail,  $siteLogo, $favicon, $timeZone, $dateFormat, $timeFormat));
          /*Success message*/
          $theMsg = '<div class="alert alert-success">the article was added with success</div>';
          redirectBack($theMsg, 'back');
        } else {

          $allowComments = isset($_POST["allowcomments"]) ?  $_POST["allowcomments"] : 0;
          $allowDate     = isset($_POST["allowdate"]) ? $_POST["allowdate"] : 0;
          $displayBreakingNews     = isset($_POST["displayBreakingNews"]) ? $_POST["displayBreakingNews"] : 0;

          $stmt = $conn->prepare("UPDATE options
        SET OptionsValue = 
            CASE 
            WHEN OptionsName = 'Allow comments' THEN ?
            WHEN OptionsName = 'Allow date in top nav' THEN ?
            WHEN OptionsName = 'display breaking news' THEN ?
            END
            WHERE OptionsName IN ('Allow comments', 'Allow date in top nav', 'display breaking news')");
          $stmt->execute(array($allowComments, $allowDate, $displayBreakingNews));
          /*Success message*/
          $theMsg = '<div class="alert alert-success">the article was added with success</div>';
          redirectBack($theMsg, 'back');
        }
      }
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