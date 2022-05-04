<?php
session_start();

require_once 'include/errors.php';
require_once 'include/class/user.php';
require_once 'include/class/db.php';

$db = new Database();

function insertCategoryCheckboxes(Database $db) {
  $db->query("SELECT ID, name FROM categories")->bind($ID, $name);
    while ($db->fetch())
    {
      $isChecked = '';
      if (isset($_GET['categories']) && in_array($ID, $_GET['categories'])) {
        $isChecked = 'checked';
      }

      echo "<div>
              <input type=\"checkbox\" name=\"categories[]\" value=\"$ID\" id=\"cat$ID\" $isChecked>
              <label for=\"cat$ID\">" . htmlspecialchars($name) . "</label>
            </div>";
    }
}

function insertPriceSlider(Database $db) {
  echo '<input type="range" step="0.01" min="0"';
  $db->query("SELECT MAX(unitPrice) FROM `products` WHERE active=1")->bind($maxPrice)->fetch();
  echo 'max="'. $maxPrice . '"';
  echo 'value="' . (isset($_GET['maxPrice']) ? $_GET['maxPrice'] : $maxPrice) . '"class="slider" id="maxPrice" name="maxPrice">
  <label for="maxPrice" id="maxPriceLabel"><?php echo "$ " . $maxPrice ?></label>';
}

$title = 'Products'; // For header, document title
$javascript = array('js/products.js');

if (User::isLoggedIn()) {
  array_push($javascript, 'js/loggedIn.js');
}

include_once 'include/header.php';
include_once 'include/navbar.php';
?>

<div class="container">
  <div class="row">
    <div class="col"></div>
    <div class="col">
      <h1>Products!</h1>
    </div>
    <div class="col"></div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card text-dark bg-light">
        <div class="card-body ">
          <form id="filter" onsubmit="getProducts(); return false;">
            <h5 class="card-title">Filters</h5>
            <p class="card-text">Choose on what you blow your money but specifically!</p>
            <input type="text" class="form-control" id="name" name="name" placeholder="Search for products"<?php
              if(isset($_GET['name'])) { echo 'value="' . $_GET['name'] . '"'; }
            ?> >
            <hr>
            <?php insertCategoryCheckboxes($db); ?>
            <hr>
            <div>
              <p class="card-text">How broke are you?</p>
              <?php insertPriceSlider($db); ?>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Filter</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-8" id="products">
    </div>
  </div>
</div>
<?php
include_once 'include/footer.php';
?>