<?php
require_once 'errors.php';
require_once 'class/user.php';
?>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php">
            <img src="img/skelly.png" alt="logo" class="logo">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="products.php">Products</a>
              </li>
            </ul>
            <ul class="navbar-nav">
              <li class="nav-item">
                <form action="products.php" method="GET">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" name="name" placeholder="c:" aria-label="c:">
                    <div class="input-group-append">
                      <input class="btn btn-secondary" type="submit" value="Search">
                    </div>
                  </div>
                </form>
              </li>
            </ul>
            <ul class="navbar-nav ms-auto">
              <?php
              if (User::isLoggedIn()) {
                echo '
                <li><a href="cart.php" class="btn btn-primary position-relative">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                  </svg>'
                . '</a></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">'
                  . $_SESSION['user']["firstName"] . ' ' . $_SESSION['user']["lastName"] .
                  (User::isAdmin() ? '<svg class="star" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>' : '') .
                  '</a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>';
                    if (User::isAdmin()) {
                      echo '<li><a class="dropdown-item" href="admin.php">Admin panel</a></li>';
                    }
                    echo '<li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item red" href="logout.php">Log out</a></li>
                  </ul>
                </li>
                ';
              }
              else {
                echo '
                <li>
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="login.php" class="btn btn-outline-primary">Log in</button></a>
                    <a href="register.php" class="btn btn-primary">Sign up</button></a>
                  </div>
                </li>
                ';
              }
              ?>
            </ul>
          </div>
        </div>
    </nav>