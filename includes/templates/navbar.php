<!--Navbar top-->
<nav class="navbar nav-top">
  <div class="container">
    <ul class="list-unstyled nav navbar-nav">
      <li><a href="">Privecy policy</a></li>
      <li><a href="">Contact us</a></li>
    </ul>
    <?php
    date_default_timezone_set($options['1']['OptionsValue']);
    $dateFormate = $options['2']['OptionsValue'] . ' ' . $options['3']['OptionsValue'];
    $get_date = date($dateFormate);

    if ($options['4']['OptionsValue'] == 1) { ?>
      <ul class="pull-right list-unstyled nav navbar-nav">
        <li><a><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $get_date; ?></a></li>
      </ul>
    <?php }
    ?>
  </div><!-- /.container -->
</nav>

<!--logo area-->
<div class="logo-area">
  <div class="container">
    <div class="logo">
      <a href="index.php"><img src="admin/uploads/<?php echo $options['5']['OptionsValue']; ?>" class="img-responsive" alt="site-logo"></a>
    </div>
  </div>
</div>
<!--Navbar main-->
<nav class="navbar nav-main">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="list-unstyled nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <?php
        /*Get the menu*/
        $menu = $conn->prepare("SELECT * FROM menus");
        $menu->execute();
        $items = $menu->fetchAll();

        foreach ($items as $item) {
          $menuItems = explode(",", $item['MenuItem']);
          foreach ($menuItems as $item) {
            echo '<li><a href="/category/' . $item . '">' . $item . '</a></li>';
          }
        }
        ?>
      </ul>
    </div>
  </div><!-- /.container -->
</nav>