<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="img/favicon.ico">

    <link rel="stylesheet" href="css/reset.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.js"></script>
    <?php
    if (isset($css)) {
        foreach ($css as $s) {
            echo "<link link rel=\"stylesheet\" href=\"$s\"></script>";
        }
    }
    if (isset($javascript)) {
        foreach ($javascript as $script) {
            echo "<script src=\"$script\"></script>";
        }
    }
    ?>
    <title>
        <?php
            if (isset($title))
            {
                echo $title;
            }
            else
            {
                echo 'Default title!';
            }
        ?>
    </title>
  </head>
  <body>