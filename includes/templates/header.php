<?php

/*Get the options from datebase */
$stmtOptions = $conn->prepare("SELECT OptionsValue FROM options WHERE OptionsID IN (2,4,5,6,8,10,11)");
$stmtOptions->execute();
$options = $stmtOptions->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="<?php echo $options['1']['OptionsValue']; ?>" />
  <meta name="viewport" content="width=device-width" ,initial-scale="1.0" />
  <title><?php getPageTitle(); ?></title>
  <link rel="icon" href="admin/uploads/<?php echo $options['6']['OptionsValue']; ?>" type="image/png" />
  <link rel="stylesheet" href="<?php echo 'admin/' . $css ?>bootstrap.css" />
  <link rel="stylesheet" href="<?php echo 'admin/' . $css ?>font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo $css ?>style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" />
</head>

<body>