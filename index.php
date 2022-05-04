<?php
session_start();

require_once 'include/errors.php';
require_once 'include/class/db.php';

function insertCategoryCards() {
  $db = new Database();
  $db->query("SELECT ID, name FROM categories")->bind($ID, $name);
    while ($db->fetch()) {
      echo '
      <div class="col-sm-3 card category-card">
      <div class="card-body">
        <h5 class="card-title">' . htmlspecialchars($name) .'</h5>
        <a href="products.php?categories%5B%5D=' . $ID .  '" class="btn btn-primary">View category</a>
        </div>
      </div>';
    }
}


$title = 'Home'; // For header, document title
include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container-fluid mt-5 gx-5">
  <div class="row gx-5">
    <!-- <h1>home</h1> -->
    <div class="col"></div>
    <div class="col-lg-8 col-12 card mb-3">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="img/skelly.png" class="img-fluid rounded-start" alt="Skelly logo">
        </div>
        <div class="col-6">
          <div class="card-body">
            <h1 class="card-title">Welcome to Skelly's Inc.</h1>
            <p class="card-text">Here at Skelly's we love milk. </p>
            <a href="products.php" class="btn btn-primary">View all products</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col"></div>
  </div>
  <div class="row gx-5">
    <div class="col"></div>
    <div class="col-lg-8 col-12 row">
    <?php insertCategoryCards() ?>
    </div>
    <div class="col"></div>
  </div>
</div>

<?php
include_once 'include/footer.php';
?>